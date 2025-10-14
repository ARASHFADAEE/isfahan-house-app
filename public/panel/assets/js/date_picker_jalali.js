// 📌 نمونه‌سازی برای هر ورودی
$(document).ready(function () {
  // 01 basic-date
  $(".basic-date").persianDatepicker();

  // 02 time-picker
  $(".date-time-picker").persianDatepicker({
    format: "HH:mm",
    onlyTimePicker: true
  });

  // 03 datetime-picker
  $(".time-picker").persianDatepicker({
    format: "YYYY/MM/DD HH:mm",
    timePicker: {
      enabled: true,
    },
  });

  // 04 gregorian-picker
  $(".picker-gregorian").persianDatepicker({
    format: "YYYY/MM/DD",
    calendarType: 'gregorian',
    toolbox: {
      calendarSwitch: {
        enabled: true
      }
    },
    gregorian: {
      showHint: false,
    },
    navigator: {
      scroll: {
        enabled: false
      }
    },
  });

  // 05 human-friendly-dates
  $(".human-friendly-dates").persianDatepicker({
    format: "dddd DD MMMM YYYY",
  });


  // 08 date-picker-limits
  $(".date-picker-limits").persianDatepicker({
    initialValue: false,
    monthPicker: {
      enabled: false,
    },
    yearPicker: {
      enabled: false,
    },
    maxDate: new persianDate().add('day', 5).valueOf(),
    minDate: new persianDate().subtract('day', 5).valueOf(),
    toolbox: {
      calendarSwitch: {
        enabled: false
      }
    },
  });

  // 09 date-picker-limits-2
  $(".date-picker-limits-2").persianDatepicker({
    initialValue: false,
    maxDate: new persianDate().add('month', 1).valueOf(),
    checkDate: function (unix) {
      return new persianDate(unix).day() != 4;
    },
    checkYear: function (year) {
      return year <= 1400;
    },
    toolbox: {
      calendarSwitch: {
        enabled: false
      }
    },
  });

  // 10 inline
  $(".inline").persianDatepicker({
    format: "YYYY/MM/DD HH:mm",
    inline: true,
  });
});
