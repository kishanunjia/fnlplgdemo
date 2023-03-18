jQuery(document).ready(function () {

	var d = new Date();
	var month = d.getMonth();
	var day = d.getDate();
	var year = d.getFullYear();

	jQuery('.datetimerange').datetimepicker({

	  ownerDocument: document,
	  contentWindow: window,
	  value: '',
	  rtl: false,
	  format: 'm/d/Y H:i',
	  formatTime: 'H:i',
	  formatDate: 'm/d/Y',
	  startDate: false, 
	  step: 60,
	  monthChangeSpinner: true,
	  closeOnDateSelect: false,
	  closeOnTimeSelect: true,
	  closeOnWithoutClick: true,
	  closeOnInputClick: true,
	  openOnFocus: true,
	  timepicker: true,
	  datepicker: true,
	  weeks: false,
	  defaultTime: false, 
	  defaultDate: false, 
	  minDate: false,
	  maxDate: false,
	  minTime: false,
	  maxTime: false,
	  minDateTime: false,
	  maxDateTime: false,
	  allowTimes: [],
	  opened: false,
	  initTime: false,
	  inline: false,
	  theme: '',
	  touchMovedThreshold: 5,
	  // callbacks
	  onSelectDate: function () {},
	  onSelectTime: function () {},
	  onChangeMonth: function () {},
	  onGetWeekOfYear: function () {},
	  onChangeYear: function () {},
	  onChangeDateTime: function () {},
	  onShow: function () {},
	  onClose: function () {},
	  onGenerate: function () {},

	  withoutCopyright: true,
	  inverseButton: false,
	  hours12: false,
	  next: 'xdsoft_next',
	  prev : 'xdsoft_prev',
	  dayOfWeekStart: 0,
	  parentID: 'body',
	  timeHeightInTimePicker: 25,
	  timepicker: true,
	  todayButton: true,
	  prevButton: true,
	  nextButton: true,
	  defaultSelect: true,

	  scrollMonth: true,
	  scrollTime: true,
	  scrollInput: true,

	  lazyInit: false,
	  mask: false,
	  validateOnBlur: true,
	  allowBlank: true,
	  yearStart: 1950,
	  yearEnd: 2050,
	  monthStart: 0,
	  monthEnd: 11,
	  style: '',
	  id: '',
	  fixed: false,
	  roundTime: 'round', // ceil, floor
	  className: '',
	  weekends: [],
	  highlightedDates: [],
	  highlightedPeriods: [],
	  allowDates : [],
	  allowDateRe : null,
	  disabledDates : [],
	  disabledWeekDays: [],
	  yearOffset: 0,
	  beforeShowDay: null,

	  enterLikeTab: true,
	  showApplyButton: false,
	  insideParent: false,	  
	});

    jQuery('.my-color-field').wpColorPicker();
    
});