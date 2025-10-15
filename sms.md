۱) کانفیگ (config/sms.php)

یه فایل کانفیگ ساده بساز تا تنظیمات ملی‌پیامک و قالب‌ها اینجا باشه:

<?php
// config/sms.php
return [
    'default_provider' => env('SMS_PROVIDER', 'melli'),

    'providers' => [
        'melli' => [
            'wsdl' => env('MELLI_WSDL', 'http://api.payamak-panel.com/post/Send.asmx?wsdl'),
            'username' => env('MELLI_USERNAME', ''),
            'password' => env('MELLI_PASSWORD', ''),
            // پایه‌ای که الگوها (base number) رو دارن — اگر لازم باشه
            'base_number' => env('MELLI_BASE_NUMBER', ''),
        ],
        // می‌تونی بقیه provider ها رو اینجا اضافه کنی
    ],

    // قالب‌های ساده — key ها رو داخل کد صدا می‌زنیم
    'templates' => [
        // قالبِ پترن: {patternId} و پارامترها
        'verify_code' => '@{patternId}@{code};{expire}', 
        'welcome_user' => '@{patternId}@{name}',
    ],

    // fallback providers (اختیاری)
    'fallback_providers' => [],
];


در .env هم مقادیر رو قرار بده:

SMS_PROVIDER=melli
MELLI_WSDL=http://api.payamak-panel.com/post/Send.asmx?wsdl
MELLI_USERNAME=your_username
MELLI_PASSWORD=your_password
MELLI_BASE_NUMBER=1000

۲) قرارداد (Interface)

یک interface ساده که Providerها باید پیاده‌سازی کنند:

<?php
namespace App\Modules\Sms\Contracts;

interface SmsProviderInterface
{
    /**
     * send message. returns boolean success
     */
    public function send(string $to, string $message, array $meta = []): bool;
}

۳) پیاده‌سازی ملی‌پیامک (MelliProvider)

اینجا از SoapClient استفاده می‌کنیم طبق نمونهٔ پروسیژِرال تو، ولی در قالب کلاسی و با هندل خطا.

<?php
namespace App\Modules\Sms\Providers;

use App\Modules\Sms\Contracts\SmsProviderInterface;
use SoapClient;
use Exception;
use Illuminate\Support\Facades\Log;

class MelliSmsProvider implements SmsProviderInterface
{
    protected string $wsdl;
    protected string $username;
    protected string $password;
    protected ?string $baseNumber;

    public function __construct(array $config)
    {
        $this->wsdl = $config['wsdl'] ?? 'http://api.payamak-panel.com/post/Send.asmx?wsdl';
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->baseNumber = $config['base_number'] ?? null;
    }

    public function send(string $to, string $message, array $meta = []): bool
    {
        // اطمینان از فعال بودن SOAP extension
        if (!extension_loaded('soap')) {
            Log::error('SOAP extension not loaded.');
            return false;
        }

        // disable WSDL cache like sample
        ini_set("soap.wsdl_cache_enabled","0");

        try {
            $sms = new SoapClient($this->wsdl, ["encoding" => "UTF-8"]);

            // داده‌ای که API انتظار داره (طبق نمونهٔ کاربر)
            $data = [
                "username" => $this->username,
                "password" => $this->password,
                "text" => $message,
                "to" => $to,
            ];

            // اگر api متد SendByBaseNumber3 نیاز به base number داره، ممکنه
            // لازم باشه فیلدهای اضافه هم بفرستیم. در این مثال از متد SendByBaseNumber3 استفاده می‌کنیم.
            $result = $sms->SendByBaseNumber3($data);

            // نتیجهٔ نمونه توی نمونهٔ اولیه: $sms->SendByBaseNumber3($data)->SendByBaseNumber3Result;
            $code = null;
            if (is_object($result) && property_exists($result, 'SendByBaseNumber3Result')) {
                $code = $result->SendByBaseNumber3Result;
            } elseif (is_scalar($result)) {
                $code = $result;
            }

            // بررسی سادهٔ نتیجه — API های ملی‌پیامک عددی برمی‌گردونن (مثلاً >0 موفق)
            if ($code !== null && (int)$code > 0) {
                return true;
            }

            Log::warning('MelliSmsProvider send failed', ['to' => $to, 'result' => $code, 'data' => $data]);
            return false;
        } catch (Exception $e) {
            Log::error('MelliSmsProvider exception: '.$e->getMessage(), ['to' => $to, 'message' => $message]);
            return false;
        }
    }
}


توضیح: در مثال بالا فرض کردم SendByBaseNumber3 خروجی‌ای شبیه نمونهٔ شما برمی‌گرداند (فیلد SendByBaseNumber3Result). اگر API در مستندات ملی‌پیامک خروجی یا پارامتر اسم متفاوتی دارد، آن را طبق مستندات جایگزین کنید.

۴) SmsManager (مدیریت و قالب‌گذاری)

قابلیت template کردن، resolve provider، لاگ و fallback رو اینجا پیاده می‌کنیم:

<?php
namespace App\Modules\Sms\Services;

use App\Modules\Sms\Contracts\SmsProviderInterface;
use App\Modules\Sms\Models\SmsLog;
use App\Modules\Sms\Providers\MelliSmsProvider;

class SmsManager
{
    protected $to;
    protected $template;
    protected $message;
    protected $providerName;

    public function to(string $to)
    {
        $this->to = $to;
        return $this;
    }

    public function template(string $key)
    {
        $tpl = config("sms.templates.$key");
        $this->template = $tpl;
        return $this;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    protected function buildMessage(array $data = [], array $meta = [])
    {
        $msg = $this->message ?? $this->template ?? '';

        // جایگزینی پارامترها مانند {code} یا {patternId}
        foreach ($data as $k => $v) {
            $msg = str_replace("{" . $k . "}", $v, $msg);
        }

        // اگر در قالب از {patternId} استفاده شده و meta['patternId'] داده شده باشه هم قرار می‌گیره
        if (strpos($msg, '{patternId}') !== false && isset($meta['patternId'])) {
            $msg = str_replace('{patternId}', $meta['patternId'], $msg);
        }

        return $msg;
    }

    protected function resolveProviderInstance(string $name): ?SmsProviderInterface
    {
        $providers = config('sms.providers');
        if (!isset($providers[$name])) {
            return null;
        }

        $cfg = $providers[$name];

        // برای الان فقط مِلّی رو پشتیبانی می‌کنیم. می‌تونی بقیه کلاس‌ها رو map کنی.
        if ($name === 'melli') {
            return new MelliSmsProvider($cfg);
        }

        // در آینده: map به کلاس‌های دیگه
        return null;
    }

    public function send(array $data = [], array $meta = []): bool
    {
        $providers = array_merge(
            [config('sms.default_provider')],
            config('sms.fallback_providers', [])
        );

        $message = $this->buildMessage($data, $meta);

        foreach ($providers as $providerName) {
            $provider = $this->resolveProviderInstance($providerName);
            if (!$provider) continue;

            $success = $provider->send($this->to, $message, $meta);

            SmsLog::create([
                'to' => $this->to,
                'message' => $message,
                'provider' => $providerName,
                'status' => $success ? 'sent' : 'failed',
            ]);

            if ($success) {
                return true;
            }
            // اگر failed بود به provider بعدی می‌ریم
        }

        return false;
    }

    // ارسال در صف
    public function queue(string $templateOrMessageKey, array $data = [], array $meta = [])
    {
        // ما می‌خواهیم job dispatch کنیم
        \App\Jobs\SendSmsJob::dispatch($this->to, $templateOrMessageKey, $data, $meta);
        return true;
    }
}

۵) مدل و migration لاگ پیامک

Migration نمونه:

Schema::create('sms_logs', function (Blueprint $table) {
    $table->id();
    $table->string('to');
    $table->text('message');
    $table->string('provider')->nullable();
    $table->string('status')->default('pending');
    $table->text('meta')->nullable(); // json
    $table->timestamps();
});


Model ساده:

<?php
namespace App\Modules\Sms\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = ['to','message','provider','status','meta'];

    protected $casts = [
        'meta' => 'array',
    ];
}

۶) Job برای ارسال در صف (SendSmsJob)

اگر خواستی ارسال غیرهم‌زمان باشه:

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Sms\Services\SmsManager;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $templateOrMessage;
    public $data;
    public $meta;

    public function __construct(string $phone, string $templateOrMessage, array $data = [], array $meta = [])
    {
        $this->phone = $phone;
        $this->templateOrMessage = $templateOrMessage;
        $this->data = $data;
        $this->meta = $meta;
    }

    public function handle()
    {
        $sms = new SmsManager();
        // اگر templateOrMessage یک کلید قالب هست، template می‌زنیم؛
        // اگر پیام خام هست، setMessage می‌کنیم.
        if (config("sms.templates.{$this->templateOrMessage}")) {
            $sms->to($this->phone)->template($this->templateOrMessage)->send($this->data, $this->meta);
        } else {
            $sms->to($this->phone)->setMessage($this->templateOrMessage)->send($this->data, $this->meta);
        }
    }
}

۷) نحوهٔ استفاده (نمونه‌ها)
ارسال فوری با پترن (مثال: verify_code)

فرض کن patternId رو توی متا می‌دی (یا داخل قالب قراردادی):

// patternId: 12345 (شماره پترن شما در ملی‌پیامک)
Sms::to('09121234567')
   ->template('verify_code')   // قالب ما: '@{patternId}@{code};{expire}'
   ->send(['code' => '4321', 'expire' => '10'], ['patternId' => 12345]);


پیامی که به SOAP می‌رسه بعد از جایگزینی ممکنه مثل این باشه:

@12345@4321;10


و طبق نمونهٔ SOAP شما، فیلد text را با این مقدار می‌فرستیم.

ارسال در صف
Sms::to('09121234567')->queue('verify_code', ['code' => '4321', 'expire' => '10'], ['patternI
