"use strict";
(function () {

	/**
	 * Global variables
	 */
	let
		userAgent = navigator.userAgent.toLowerCase(),
		isIE = userAgent.indexOf("msie") !== -1 ? parseInt(userAgent.split("msie")[1], 10) : userAgent.indexOf("trident") !== -1 ? 11 : userAgent.indexOf("edge") !== -1 ? 12 : false;

	// Unsupported browsers
	if (isIE !== false && isIE < 12) {
		console.warn("[Core] detected IE" + isIE + ", load alert");
		var script = document.createElement("script");
		script.src = "./js/support.js";
		document.querySelector("head").appendChild(script);
	}

	let
			initialDate = new Date(),

			$document = $(document),
			$window = $(window),
			$html = $("html"),
			$body = $("body"),

			isDesktop = $html.hasClass("desktop"),
			isRtl = $html.attr("dir") === "rtl",
			isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),

			windowReady = false,
			isNoviBuilder = false,
			loaderTimeoutId,

			plugins = {
				preloader:               $(".preloader"),
				bootstrapTooltip:        $("[data-bs-toggle='tooltip']"),
				bootstrapModalDialog:    $('.modal'),
				bootstrapTabs:           $(".tabs-custom"),
				bootstrapCollapse:       $(".card-custom"),
				rdNavbar:                $(".rd-navbar"),
				materialParallax:        $(".parallax-container"),
				rdMailForm:              $(".rd-mailform"),
				rdInputLabel:            $(".form-label"),
				regula:                  $("[data-constraints]"),
				rdRange:                 $('.rd-range'),
				wow:                     $(".wow"),
				owl:                     $(".owl-carousel"),
				swiper:                  $(".swiper-slider"),
				search:                  $(".rd-search"),
				searchResults:           $('.rd-search-results'),
				statefulButton:          $('.btn-stateful'),
				popover:                 $('[data-bs-toggle="popover"]'),
				viewAnimate:             $('.view-animate'),
				radio:                   $("input[type='radio']"),
				checkbox:                $("input[type='checkbox']"),
				customToggle:            $("[data-custom-toggle]"),
				captcha:                 $('.recaptcha'),
				lightGallery:            $("[data-lightgallery='group']"),
				lightGalleryItem:        $("[data-lightgallery='item']"),
				lightDynamicGalleryItem: $("[data-lightgallery='dynamic']"),
				mailchimp:               $('.mailchimp-mailform'),
				campaignMonitor:         $('.campaign-mailform'),
				copyrightYear:           $(".copyright-year"),
				selectFilter:            $("select"),
				inlineToggle:            $('.inline-toggle'),
				focusToggle:             $('.focus-toggle'),
				stepper:                 $("input[type='number']"),
				radioPanel:              $('.radio-panel .radio-inline'),
				slick:                   $('.slick-slider'),
				videoOverlay:            $('.video-overlay'),
				counter:                 document.querySelectorAll('.counter'),
				progressLinear:          document.querySelectorAll('.progress-linear'),
				progressCircle:          document.querySelectorAll('.progress-bar-js'),
				countdownCircle:         document.querySelectorAll('.countdown-circle-container'),
				countdown:               document.querySelectorAll('.countdown'),
				doughnutChart:           document.querySelectorAll('.doughnut-chart'),
				lazyIFrame:              $('iframe[data-src]'),
				themeSwitcher:           $("[data-theme-name]")
			};

	/**
	 * @desc Check the element was been scrolled into the view
	 * @param {object} elem - jQuery object
	 * @return {boolean}
	 */
	function isScrolledIntoView(elem) {
		if (isNoviBuilder) return true;
		return elem.offset().top + elem.outerHeight() >= $window.scrollTop() && elem.offset().top <= $window.scrollTop() + $window.height();
	}

	/**
	 * @desc Calls a function when element has been scrolled into the view
	 * @param {object} element - jQuery object
	 * @param {function} func - init function
	 */
	function lazyInit(element, func) {
		var scrollHandler = function () {
			if ((!element.hasClass('lazy-loaded') && (isScrolledIntoView(element)))) {
				func.call(element);
				element.addClass('lazy-loaded');
			}
		};

		scrollHandler();
		$window.on('scroll', scrollHandler);
	}

	// Initialize scripts that require a loaded window
	$window.on('load', function () {
		// Page loader & Page transition
		if (plugins.preloader.length && !isNoviBuilder) {
			pageTransition({
				target:            document.querySelector('.page'),
				delay:             0,
				duration:          500,
				classIn:           'fadeIn',
				classOut:          'fadeOut',
				classActive:       'animated',
				conditions:        function (event, link) {
					return link && !/(\#|javascript:void\(0\)|callto:|tel:|mailto:|:\/\/)/.test(link) && !event.currentTarget.hasAttribute('data-lightgallery');
				},
				onTransitionStart: function (options) {
					setTimeout(function () {
						plugins.preloader.removeClass('loaded');
					}, options.duration * .75);
				},
				onReady:           function () {
					plugins.preloader.addClass('loaded');
					windowReady = true;
				}
			});
		}

		// Countdown
		if (plugins.countdownCircle.length) {

			for (let i = 0; i < plugins.countdownCircle.length; i++) {
				let
						node = plugins.countdownCircle[i],
						countdown = aCountdown({
							node:  node,
							from:  node.getAttribute('data-from'),
							to:    node.getAttribute('data-to'),
							count: node.getAttribute('data-count'),
							tick:  100,
						});
			}
		}

		// jQuery Countdown
		if (plugins.countdown.length) {
			for (let i = 0; i < plugins.countdown.length; i++) {
				var $countDownItem = $(plugins.countdown[i]),
						d = new Date(),
						type = $countDownItem.attr('data-type'),
						time = $countDownItem.attr('data-time'),
						format = $countDownItem.attr('data-format'),
						settings = [];

				// Classic style
				if ($countDownItem.attr('data-style') === 'short') {
					settings['labels'] = ['Yeas', 'Mons', 'Weks', 'Days', 'Hrs', 'Mins', 'Secs'];
				}

				d.setTime(Date.parse(time)).toLocaleString();
				settings[type] = d;
				settings['format'] = format;
				$countDownItem.countdown(settings);
			}
		}

		// Progress Bar
		if (plugins.progressLinear) {
			for (let i = 0; i < plugins.progressLinear.length; i++) {
				let
						container = plugins.progressLinear[i],
						bar = container.querySelector('.progress-bar-linear'),
						duration = container.getAttribute('data-duration') || 1000,
						counter = aCounter({
							node:     container.querySelector('.progress-value'),
							duration: duration,
							onStart:  function () {
								this.custom.bar.style.width = this.params.to + '%';
							}
						});

				bar.style.transitionDuration = duration / 1000 + 's';
				counter.custom = {
					container: container,
					bar:       bar,
					onScroll:  (function () {
						if ((Util.inViewport(this.custom.container) && !this.custom.container.classList.contains('animated')) || isNoviBuilder) {
							this.run();
							this.custom.container.classList.add('animated');
						}
					}).bind(counter),
					onBlur:    (function () {
						this.params.to = parseInt(this.params.node.textContent, 10);
						this.run();
					}).bind(counter)
				};

				if (isNoviBuilder) {
					counter.run();
					counter.params.node.addEventListener('blur', counter.custom.onBlur);
				} else {
					counter.custom.onScroll();
					document.addEventListener('scroll', counter.custom.onScroll);
				}
			}
		}

		// Progress Circle
		if (plugins.progressCircle) {
			for (let i = 0; i < plugins.progressCircle.length; i++) {
				let
						container = plugins.progressCircle[i],
						counter = aCounter({
							node:     container.querySelector('.progress-circle-counter'),
							duration: 500,
							onUpdate: function (value) {
								this.custom.bar.render(value * 3.6);
							}
						});

				counter.params.onComplete = counter.params.onUpdate;

				counter.custom = {
					container: container,
					bar:       aProgressCircle({node: container.querySelector('.progress-circle-bar')}),
					onScroll:  (function () {
						if (Util.inViewport(this.custom.container) && !this.custom.container.classList.contains('animated')) {
							this.run();
							this.custom.container.classList.add('animated');
						}
					}).bind(counter),
					onBlur:    (function () {
						this.params.to = parseInt(this.params.node.textContent, 10);
						this.run();
					}).bind(counter)
				};

				if (isNoviBuilder) {
					counter.run();
					counter.params.node.addEventListener('blur', counter.custom.onBlur);
				} else {
					counter.custom.onScroll();
					window.addEventListener('scroll', counter.custom.onScroll);
				}
			}
		}

		// doughnutChart
		if (plugins.doughnutChart.length) {
			for (var i = 0; i < plugins.doughnutChart.length; i++) {
				var $element = $(plugins.doughnutChart[i]),
						$chartDataList = $element.find('.doughnut-chart-list').first();

				if ($chartDataList) {
					var ratioValues = [],
							chartDataListItems = $chartDataList.find('> li');

					for (var k = 0; k < chartDataListItems.length; k++) {
						var value = Math.abs(chartDataListItems[k].getAttribute('data-value') * 1);
						ratioValues.push({});
						ratioValues[k].title = chartDataListItems[k].innerHTML;
						ratioValues[k].value = value ? value : 10;
					}

					$element.drawDoughnutChart(
							ratioValues, {
								segmentShowStroke:     false,
								segmentStrokeWidth:    0,
								baseColor:             '#f5f5f5',
								baseOffset:            0,
								edgeOffset:            1,
								percentageInnerCutout: 85
							}
					);
				}
			}
		}
	});

	// Initialize All Scripts
	$(function () {
		isNoviBuilder = window.xMode;

		/**
		 * Wrapper to eliminate json errors
		 * @param {string} str - JSON string
		 * @returns {object} - parsed or empty object
		 */
		function parseJSON ( str ) {
			try {
				if ( str )  return JSON.parse( str );
				else return {};
			} catch ( error ) {
				console.warn( error );
				return {};
			}
		}

		/**
		 * @desc Sets slides background images from attribute 'data-slide-bg'
		 * @param {object} swiper - swiper instance
		 */
		function setBackgrounds(swiper) {
			let swipersBg = swiper.el.querySelectorAll('[data-slide-bg]');

			for (let i = 0; i < swipersBg.length; i++) {
				let swiperBg = swipersBg[i];
				swiperBg.style.backgroundImage = 'url(' + swiperBg.getAttribute('data-slide-bg') + ')';
				swiperBg.style.backgroundSize = 'cover';
			}
		}

		/**
		 * @desc Sets hover autoplay  from attribute 'data-autoplay-hover'
		 * @param {object} swiper - swiper instance
		 */
		function setHoverAutoplay(swiper) {
			if (swiper.el.getAttribute('data-autoplay-hover') === 'true') {
				let hoverEvent;
				swiper.el.addEventListener('mouseenter', function (e) {
					hoverEvent = setInterval(function () {
						swiper.slideNext();
					}, +swiper.el.getAttribute('data-autoplay-hover-delay') );
				});
				swiper.el.addEventListener('mouseleave', function (e) {
					clearInterval(hoverEvent);
				})
			}
		}
		
		/**
		 * toggleSwiperCaptionAnimation
		 * @description  toggle swiper animations on active slides
		 */
		function toggleSwiperCaptionAnimation(swiper) {
			let prevSlide = $(swiper.$el[0]),
				nextSlide = $(swiper.slides[swiper.activeIndex]);
			
			prevSlide
				.find("[data-caption-animate]")
				.each(function () {
					let $this = $(this);
					$this
						.removeClass("animated")
						.removeClass($this.attr("data-caption-animate"))
						.addClass("not-animated");
				});
			
			nextSlide
				.find("[data-caption-animate]")
				.each(function () {
					let $this = $(this),
						delay = $this.attr("data-caption-delay");
					
					
					if (!isNoviBuilder) {
						setTimeout(function () {
							$this
								.removeClass("not-animated")
								.addClass($this.attr("data-caption-animate"))
								.addClass("animated");
						}, delay ? parseInt(delay) : 0);
					} else {
						$this
							.removeClass("not-animated")
					}
				});
		}

		/**
		 * @desc Swiper Render Bullet function
		 */
		function renderBullet(index, className) {
			return '<span class="' + className + '"><span>' + ((index + 1) < 10 ? ('0' + (index + 1)) : (index + 1)) + '</span></span>';
		}

		/**
		 * initOwlCarousel
		 * @description  Init owl carousel plugin
		 */
		function initOwlCarousel(c) {
			var aliaces = ["-", "-sm-", "-md-", "-lg-", "-xl-", "-xxl-"],
					values = [0, 576, 768, 992, 1200, 1600],
					responsive = {};

			for (var j = 0; j < values.length; j++) {
				responsive[values[j]] = {};
				for (var k = j; k >= -1; k--) {
					if (!responsive[values[j]]["items"] && c.attr("data" + aliaces[k] + "items")) {
						responsive[values[j]]["items"] = k < 0 ? 1 : parseInt(c.attr("data" + aliaces[k] + "items"), 10);
					}
					if (!responsive[values[j]]["stagePadding"] && responsive[values[j]]["stagePadding"] !== 0 && c.attr("data" + aliaces[k] + "stage-padding")) {
						responsive[values[j]]["stagePadding"] = k < 0 ? 0 : parseInt(c.attr("data" + aliaces[k] + "stage-padding"), 10);
					}
					if (!responsive[values[j]]["margin"] && responsive[values[j]]["margin"] !== 0 && c.attr("data" + aliaces[k] + "margin")) {
						responsive[values[j]]["margin"] = k < 0 ? 30 : parseInt(c.attr("data" + aliaces[k] + "margin"), 10);
					}
				}
			}

			c.on("initialized.owl.carousel", function (event) {
				var $carousel = $(event.currentTarget);
				// Enable custom pagination
				if (c.attr('data-dots-custom')) {
					var customPag = $($carousel.attr("data-dots-custom")),
							active = 0;

					if ($carousel.attr('data-active')) {
						active = parseInt($carousel.attr('data-active'), 10);
					}

					$carousel.trigger('to.owl.carousel', [active, 300, true]);
					customPag.find("[data-owl-item='" + active + "']").addClass("active");

					customPag.find("[data-owl-item]").on('click', function (e) {
						e.preventDefault();
						$carousel.trigger('to.owl.carousel', [parseInt(this.getAttribute("data-owl-item"), 10), 300, true]);
					});

					$carousel.on("translate.owl.carousel", function (event) {
						customPag.find(".active").removeClass("active");
						customPag.find("[data-owl-item='" + event.item.index + "']").addClass("active")
					});
				}

				// Enable Custom Navigation
				if (c.attr('data-nav-custom')) {
					var customNav = $carousel.parents($carousel.attr("data-nav-custom"));
					// Custom Navigation Events
					customNav.find(".owl-arrow-next").click(function (e) {
						e.preventDefault();
						$carousel.trigger('next.owl.carousel');
					});
					customNav.find(".owl-arrow-prev").click(function (e) {
						e.preventDefault();
						$carousel.trigger('prev.owl.carousel');
					});
				}
			});

			c.on("initialized.owl.carousel", function (event) {
				initLightGalleryItem(c.find('[data-lightgallery="item"]'), 'lightGallery-in-carousel');
			});

			c.owlCarousel({
				autoplay:           isNoviBuilder ? false : c.attr("data-autoplay") === "true",
				autoplayTimeout:    c.data("autoplay-timeout"),
				autoplayHoverPause: c.data("autoplay-hover-pause"),
				autoplaySpeed:      c.data("data-autoplay-speed"),
				center:             c.data("data-center"),
				startPosition:      c.data("data-start-position"),
				loop:               isNoviBuilder ? false : c.attr("data-loop") === "true",
				items:              1,
				rtl:                isRtl,
				autoWidth:          c.data("data-autowidth") === "true",
				autoHeight:         c.data("data-autoheight") === "true",
				dotsContainer:      c.attr("data-pagination-class") || false,
				navContainer:       c.attr("data-navigation-class") || false,
				mouseDrag:          isNoviBuilder ? false : c.attr("data-mouse-drag") !== "false",
				touchDrag:          c.data("data-touch-drag"),
				dragEndSpeed:       c.data("data-drag-end-speed"),
				nav:                c.attr('data-nav') === 'true',
				navSpeed:           c.data("data-nav-speed"),
				dots:               c.attr("data-dots") === "true",
				dotsSpeed:          c.data("dots-speed"),
				dotsEach:           c.attr("data-dots-each") ? parseInt(c.attr("data-dots-each"), 10) : false,
				animateIn:          c.attr('data-animation-in') ? c.attr('data-animation-in') : false,
				animateOut:         c.attr('data-animation-out') ? c.attr('data-animation-out') : false,
				lazyLoad:           c.data("data-lazy-load"),
				responsive:         responsive,
				navText:            function () {
					try {
						return JSON.parse(c.attr("data-nav-text"));
					} catch (e) {
						return [];
					}
				}(),
				navClass:           function () {
					try {
						return JSON.parse(c.attr("data-nav-class"));
					} catch (e) {
						return ['owl-prev', 'owl-next'];
					}
				}()
			});

			// Mousewheel plugin
			var owl = $('.owl-mousewheel');
			owl.on('mousewheel', '.owl-stage', function (e) {
				var curr = $(this);
				if (e.deltaY > 0) {
					curr.trigger('prev.owl', [800]);
				} else {
					curr.trigger('next.owl', [800]);
				}
				e.preventDefault();
				e.stopImmediatePropagation();
			});

		}

		/**
		 * @desc Initialize the gallery with set of images
		 * @param {object} itemsToInit - jQuery object
		 * @param {string} [addClass] - additional gallery class
		 */
		function initLightGallery(itemsToInit, addClass) {
			if (!isNoviBuilder) {
				$(itemsToInit).lightGallery({
					thumbnail: $(itemsToInit).attr("data-lg-thumbnail") !== "false",
					selector:  "[data-lightgallery='item']",
					autoplay:  $(itemsToInit).attr("data-lg-autoplay") === "true",
					pause:     parseInt($(itemsToInit).attr("data-lg-autoplay-delay")) || 5000,
					addClass:  addClass,
					mode:      $(itemsToInit).attr("data-lg-animation") || "lg-slide",
					loop:      $(itemsToInit).attr("data-lg-loop") !== "false"
				});
			}
		}

		/**
		 * @desc Initialize the gallery with dynamic addition of images
		 * @param {object} itemsToInit - jQuery object
		 * @param {string} [addClass] - additional gallery class
		 */
		function initDynamicLightGallery(itemsToInit, addClass) {
			if (!isNoviBuilder) {
				$(itemsToInit).on("click", function () {
					$(itemsToInit).lightGallery({
						thumbnail: $(itemsToInit).attr("data-lg-thumbnail") !== "false",
						selector:  "[data-lightgallery='item']",
						autoplay:  $(itemsToInit).attr("data-lg-autoplay") === "true",
						pause:     parseInt($(itemsToInit).attr("data-lg-autoplay-delay")) || 5000,
						addClass:  addClass,
						mode:      $(itemsToInit).attr("data-lg-animation") || "lg-slide",
						loop:      $(itemsToInit).attr("data-lg-loop") !== "false",
						dynamic:   true,
						dynamicEl: JSON.parse($(itemsToInit).attr("data-lg-dynamic-elements")) || []
					});
				});
			}
		}

		/**
		 * @desc Initialize the gallery with one image
		 * @param {object} itemToInit - jQuery object
		 * @param {string} [addClass] - additional gallery class
		 */
		function initLightGalleryItem(itemToInit, addClass) {
			if (!isNoviBuilder) {
				$(itemToInit).lightGallery({
					selector:            "this",
					addClass:            addClass,
					counter:             false,
					youtubePlayerParams: {
						modestbranding: 1,
						showinfo:       0,
						rel:            0,
						controls:       0
					},
					vimeoPlayerParams:   {
						byline:   0,
						portrait: 0
					}
				});
			}
		}

		/**
		 * Live Search
		 * @description  create live search results
		 */
		function liveSearch(options) {
			$('#' + options.live).removeClass('cleared').html();
			options.current++;
			options.spin.addClass('loading');
			$.get(handler, {
				s:          decodeURI(options.term),
				liveSearch: options.live,
				dataType:   "html",
				liveCount:  options.liveCount,
				filter:     options.filter,
				template:   options.template
			}, function (data) {
				options.processed++;
				var live = $('#' + options.live);
				if ((options.processed === options.current) && !live.hasClass('cleared')) {
					live.find('> #search-results').removeClass('active');
					live.html(data);
					setTimeout(function () {
						live.find('> #search-results').addClass('active');
					}, 50);
				}
				options.spin.parents('.rd-search').find('.input-group-addon').removeClass('loading');
			})
		}

		/**
		 * @desc Attach form validation to elements
		 * @param {object} elements - jQuery object
		 */
		function attachFormValidator(elements) {
			// Custom validator - phone number
			regula.custom({
				name:           'PhoneNumber',
				defaultMessage: 'Invalid phone number format',
				validator:      function () {
					if (this.value === '') return true;
					else return /^(\+\d)?[0-9\-\(\) ]{5,}$/i.test(this.value);
				}
			});

			for (var i = 0; i < elements.length; i++) {
				var o = $(elements[i]), v;
				o.addClass("form-control-has-validation").after("<span class='form-validation'></span>");
				v = o.parent().find(".form-validation");
				if (v.is(":last-child")) o.addClass("form-control-last-child");
			}

			elements.on('input change propertychange blur', function (e) {
				var $this = $(this), results;

				if (e.type !== "blur") if (!$this.parent().hasClass("has-error")) return;
				if ($this.parents('.rd-mailform').hasClass('success')) return;

				if ((results = $this.regula('validate')).length) {
					for (i = 0; i < results.length; i++) {
						$this.siblings(".form-validation").text(results[i].message).parent().addClass("has-error");
					}
				} else {
					$this.siblings(".form-validation").text("").parent().removeClass("has-error")
				}
			}).regula('bind');

			var regularConstraintsMessages = [
				{
					type:       regula.Constraint.Required,
					newMessage: "The text field is required."
				},
				{
					type:       regula.Constraint.Email,
					newMessage: "The email is not a valid email."
				},
				{
					type:       regula.Constraint.Numeric,
					newMessage: "Only numbers are required"
				},
				{
					type:       regula.Constraint.Selected,
					newMessage: "Please choose an option."
				}
			];

			for (var i = 0; i < regularConstraintsMessages.length; i++) {
				var regularConstraint = regularConstraintsMessages[i];

				regula.override({
					constraintType: regularConstraint.type,
					defaultMessage: regularConstraint.newMessage
				});
			}
		}

		/**
		 * @desc Check if all elements pass validation
		 * @param {object} elements - object of items for validation
		 * @param {object} captcha - captcha object for validation
		 * @return {boolean}
		 */
		function isValidated(elements, captcha) {
			var results, errors = 0;

			if (elements.length) {
				for (var j = 0; j < elements.length; j++) {

					var $input = $(elements[j]);
					if ((results = $input.regula('validate')).length) {
						for (k = 0; k < results.length; k++) {
							errors++;
							$input.siblings(".form-validation").text(results[k].message).parent().addClass("has-error");
						}
					} else {
						$input.siblings(".form-validation").text("").parent().removeClass("has-error")
					}
				}

				if (captcha) {
					if (captcha.length) {
						return validateReCaptcha(captcha) && errors === 0
					}
				}

				return errors === 0;
			}
			return true;
		}

		/**
		 * @desc Validate google reCaptcha
		 * @param {object} captcha - captcha object for validation
		 * @return {boolean}
		 */
		function validateReCaptcha(captcha) {
			var captchaToken = captcha.find('.g-recaptcha-response').val();

			if (captchaToken.length === 0) {
				captcha
				.siblings('.form-validation')
				.html('Please, prove that you are not robot.')
				.addClass('active');
				captcha
				.closest('.form-wrap')
				.addClass('has-error');

				captcha.on('propertychange', function () {
					var $this = $(this),
							captchaToken = $this.find('.g-recaptcha-response').val();

					if (captchaToken.length > 0) {
						$this
						.closest('.form-wrap')
						.removeClass('has-error');
						$this
						.siblings('.form-validation')
						.removeClass('active')
						.html('');
						$this.off('propertychange');
					}
				});

				return false;
			}

			return true;
		}

		/**
		 * @desc Initialize Google reCaptcha
		 */
		window.onloadCaptchaCallback = function () {
			for (var i = 0; i < plugins.captcha.length; i++) {
				var $capthcaItem = $(plugins.captcha[i]);

				grecaptcha.render(
						$capthcaItem.attr('id'),
						{
							sitekey:  $capthcaItem.attr('data-sitekey'),
							size:     $capthcaItem.attr('data-size') ? $capthcaItem.attr('data-size') : 'normal',
							theme:    $capthcaItem.attr('data-theme') ? $capthcaItem.attr('data-theme') : 'light',
							callback: function (e) {
								$('.recaptcha').trigger('propertychange');
							}
						}
				);
				$capthcaItem.after("<span class='form-validation'></span>");
			}
		};

		/**
		 * Init Bootstrap tooltip
		 * @description  calls a function when need to init bootstrap tooltips
		 */
		function initBootstrapTooltip(tooltipPlacement) {
			if (window.innerWidth < 576) {
				plugins.bootstrapTooltip.tooltip('dispose');
				plugins.bootstrapTooltip.tooltip({
					placement: 'bottom'
				});
			} else {
				plugins.bootstrapTooltip.tooltip('dispose');
				plugins.bootstrapTooltip.tooltip({
					placement: tooltipPlacement
				});
			}
		}

		/**
		 * Google ReCaptcha
		 * @description Enables Google ReCaptcha
		 */
		if (plugins.captcha.length) {
			$.getScript("//www.google.com/recaptcha/api.js?onload=onloadCaptchaCallback&render=explicit&hl=en");
		}

		/**
		 * Is Mac os
		 */
		if (navigator.platform.match(/(Mac)/i)) {
			$html.addClass("mac-os");
		}

		/**
		 * Is Firefox
		 */
		if (typeof InstallTrigger !== 'undefined') $html.addClass("firefox");

		/**
		 * IE Polyfills
		 * @description  Adds some loosing functionality to IE browsers
		 */
		if (isIE) {
			if (isIE === 12) $html.addClass("ie-edge");
			if (isIE === 11) $html.addClass("ie-11");
			if (isIE < 10) $html.addClass("lt-ie-10");
			if (isIE < 11) $html.addClass("ie-10");
		}

		/**
		 * Bootstrap Tooltips
		 * @description Activate Bootstrap Tooltips
		 */
		if (plugins.bootstrapTooltip.length) {
			var tooltipPlacement = plugins.bootstrapTooltip.attr('data-bs-placement');
			initBootstrapTooltip(tooltipPlacement);

			$window.on('resize orientationchange', function () {
				initBootstrapTooltip(tooltipPlacement);
			})
		}

		/**
		 * bootstrapModalDialog
		 * @description Stap vioeo in bootstrapModalDialog
		 */
		if (plugins.bootstrapModalDialog.length > 0) {
			for (var i = 0; i < plugins.bootstrapModalDialog.length; i++) {
				var modalItem = $(plugins.bootstrapModalDialog[i]);

				modalItem.on('hidden.bs.modal', $.proxy(function () {
					var activeModal = $(this),
							rdVideoInside = activeModal.find('video'),
							youTubeVideoInside = activeModal.find('iframe');

					if (rdVideoInside.length) {
						rdVideoInside[0].pause();
					}

					if (youTubeVideoInside.length) {
						var videoUrl = youTubeVideoInside.attr('src');

						youTubeVideoInside
						.attr('src', '')
						.attr('src', videoUrl);
					}
				}, modalItem))
			}
		}

		/**
		 * Popovers
		 * @description Enables Popovers plugin
		 */
		if (plugins.popover.length) {
			if (window.innerWidth < 767) {
				plugins.popover.attr('data-bs-placement', 'bottom');
				plugins.popover.popover();
			} else {
				plugins.popover.popover();
			}
		}

		/**
		 * Bootstrap Buttons
		 * @description  Enable Bootstrap Buttons plugin
		 */
		if (plugins.statefulButton.length) {
			$(plugins.statefulButton).on('click', function () {
				var statefulButtonLoading = $(this).button('loading');

				setTimeout(function () {
					statefulButtonLoading.button('reset')
				}, 2000);
			})
		}

		/**
		 * Bootstrap tabs
		 * @description Activate Bootstrap Tabs
		 */

		if (plugins.bootstrapTabs.length) {
			for (let i = 0; i < plugins.bootstrapTabs.length; i++) {
				let $bootstrapTab = $(plugins.bootstrapTabs[i]);

				let tabs = $bootstrapTab.find('.nav li a');
				for (var t = 0; t < tabs.length; t++) {
					let tab = $(tabs[t]);
					let	target = $(tab.attr('href')) ;

					tab.removeClass('active show');
					target.removeClass('active show');

					if (t === 0) {
						tab.addClass('active show');
						target.addClass('active show');
					}
				}

				// Nav plugin
				if ($bootstrapTab.attr('data-nav') === 'true') {

					let $buttonPrev = $($bootstrapTab.find('[data-nav-prev]')).first(),
						$buttonNext = $($bootstrapTab.find('[data-nav-next]')).first();

					if ($buttonPrev && $buttonNext) {
						let isClicked = true,
							currentIndex = 0,
							$nav = $($bootstrapTab.find('.nav')),
							navItemsLength = $nav.children().length;


						for (let z = 0; z < navItemsLength; z++) {
							$($nav.find('li:eq(' + z + ') a')).on('hidden.bs.tab', function () {
								isClicked = true;
							});
							$($nav.find('li:eq(' + z + ') a')).on('shown.bs.tab', function () {
								isClicked = true;
							});
						}

						$buttonPrev.on('click', (function ($nav) {
							return function () {
								let prevIndex = $($nav.find('.active')).parent().index() - 1;

								if ((currentIndex != prevIndex) && prevIndex >= 0 && isClicked) {
									currentIndex = prevIndex;
									isClicked = false;
									$($nav.find('li:eq(' + prevIndex + ') a')).tab('show');
								}
							}
						})($nav));

						$buttonNext.on('click', (function ($nav) {
							return function () {
								let nextIndex = $($nav.find('.active')).parent().index() + 1;

								if ((currentIndex != nextIndex) && (nextIndex < navItemsLength) && isClicked) {
									currentIndex = nextIndex;
									isClicked = false;
									$($nav.find('li:eq(' + nextIndex + ') a')).tab('show');
								}
							}
						})($nav));
					}
				}
			}
		}

		/**
		 * Bootstrap Collapse
		 */
		if (plugins.bootstrapCollapse.length) {
			for (var i = 0; i < plugins.bootstrapCollapse.length; i++) {
				var $bootstrapCollapseItem = $(plugins.bootstrapCollapse[i]);

				$bootstrapCollapseItem.on('show.bs.collapse', (function ($bootstrapCollapseItem) {
					return function () {
						$bootstrapCollapseItem.addClass('active');
					};
				})($bootstrapCollapseItem));

				$bootstrapCollapseItem.on('hide.bs.collapse', (function ($bootstrapCollapseItem) {
					return function () {
						$bootstrapCollapseItem.removeClass('active');
					};
				})($bootstrapCollapseItem));
			}
		}

		/**
		 * Copyright Year
		 * @description  Evaluates correct copyright year
		 */
		if (plugins.copyrightYear.length) {
			plugins.copyrightYear.text(initialDate.getFullYear());
		}

		/**
		 * Select2
		 * @description Enables select2 plugin
		 */
		if (plugins.selectFilter.length) {
			for (var i = 0; i < plugins.selectFilter.length; i++) {
				var select = $(plugins.selectFilter[i]);
				let parent = select.closest('div')
				select.select2({
					dropdownParent:          $( parent ),
					placeholder:             select.attr( 'data-placeholder' ) || null,
					minimumResultsForSearch: select.attr( 'data-minimum-results-search' ) || Infinity,
					containerCssClass:       select.attr( 'data-container-class' ) || null,
					dropdownCssClass:        select.attr( 'data-dropdown-class' ) || null
				});
			}
		}

		/**
		 * Radio
		 * @description Add custom styling options for input[type="radio"]
		 */
		if (plugins.radio.length) {
			for (var i = 0; i < plugins.radio.length; i++) {
				$(plugins.radio[i]).addClass("radio-custom").after("<span class='radio-custom-dummy'></span>")
			}
		}

		/**
		 * Checkbox
		 * @description Add custom styling options for input[type="checkbox"]
		 */
		if (plugins.checkbox.length) {
			for (var i = 0; i < plugins.checkbox.length; i++) {
				$(plugins.checkbox[i]).addClass("checkbox-custom").after("<span class='checkbox-custom-dummy'></span>")
			}
		}

		/**
		 * UI To Top
		 * @description Enables ToTop Button
		 */
		if (isDesktop && !isNoviBuilder) {
			$().UItoTop({
				easingType:     'easeOutQuad',
				containerClass: 'ui-to-top fa fa-angle-up'
			});
		}

		/**
		 * Owl carousel
		 * @description Enables Owl carousel plugin
		 */
		if (plugins.owl.length) {
			for (var i = 0; i < plugins.owl.length; i++) {
				var c = $(plugins.owl[i]);
				plugins.owl[i].owl = c;

				initOwlCarousel(c);
			}
		}

		// Filter carousel items
		var notCarouselItems = [];
		for (var z = 0; z < plugins.lightGalleryItem.length; z++) {
			if (!$(plugins.lightGalleryItem[z]).parents('.owl-carousel').length &&
					!$(plugins.lightGalleryItem[z]).parents('.swiper-slider').length &&
					!$(plugins.lightGalleryItem[z]).parents('.slick-slider').length) {
				notCarouselItems.push(plugins.lightGalleryItem[z]);
			}
		}

		plugins.lightGalleryItem = notCarouselItems;

		if (plugins.lightGallery.length) {
			for (var i = 0; i < plugins.lightGallery.length; i++) {
				initLightGallery(plugins.lightGallery[i]);
			}
		}

		if (plugins.lightGalleryItem.length) {
			for (var i = 0; i < plugins.lightGalleryItem.length; i++) {
				initLightGalleryItem(plugins.lightGalleryItem[i]);
			}
		}

		if (plugins.lightDynamicGalleryItem.length) {
			for (var i = 0; i < plugins.lightDynamicGalleryItem.length; i++) {
				initDynamicLightGallery(plugins.lightDynamicGalleryItem[i]);
			}
		}

		/**
		 * RD Navbar
		 * @description Enables RD Navbar plugin
		 */
		if (plugins.rdNavbar.length) {
			var aliaces, i, j, len, value, values, responsiveNavbar;

			aliaces = ["-", "-sm-", "-md-", "-lg-", "-xl-", "-xxl-"];
			values = [0, 576, 768, 992, 1200, 1600];
			responsiveNavbar = {};

			for (i = j = 0, len = values.length; j < len; i = ++j) {
				value = values[i];
				if (!responsiveNavbar[values[i]]) {
					responsiveNavbar[values[i]] = {};
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'layout')) {
					responsiveNavbar[values[i]].layout = plugins.rdNavbar.attr('data' + aliaces[i] + 'layout');
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'device-layout')) {
					responsiveNavbar[values[i]]['deviceLayout'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'device-layout');
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'hover-on')) {
					responsiveNavbar[values[i]]['focusOnHover'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'hover-on') === 'true';
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'auto-height')) {
					responsiveNavbar[values[i]]['autoHeight'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'auto-height') === 'true';
				}

				if (isNoviBuilder) {
					responsiveNavbar[values[i]]['stickUp'] = false;
				} else if (plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up')) {
					responsiveNavbar[values[i]]['stickUp'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up') === 'true';
				}

				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up-offset')) {
					responsiveNavbar[values[i]]['stickUpOffset'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up-offset');
				}
			}

			plugins.rdNavbar.RDNavbar({
				anchorNav:    !isNoviBuilder,
				stickUpClone: (plugins.rdNavbar.attr("data-stick-up-clone") && !isNoviBuilder) ? plugins.rdNavbar.attr("data-stick-up-clone") === 'true' : false,
				responsive:   responsiveNavbar,
				callbacks:    {
					onStuck:        function () {
						var navbarSearch = this.$element.find('.rd-search input');

						if (navbarSearch) {
							navbarSearch.val('').trigger('propertychange');
						}

						var navbarSelect = plugins.rdNavbar.find('select');
						if (navbarSelect.length) {
							navbarSelect.select2("close");
						}
					},
					onDropdownOver: function () {
						return !isNoviBuilder;
					},
					onUnstuck:      function () {
						if (this.$clone === null)
							return;

						var navbarSearch = this.$clone.find('.rd-search input');

						if (navbarSearch) {
							navbarSearch.val('').trigger('propertychange');
							navbarSearch.trigger('blur');
						}

					}
				}
			});

			if (plugins.rdNavbar.attr("data-body-class")) {
				document.body.className += ' ' + plugins.rdNavbar.attr("data-body-class");
			}
		}

		/**
		 * RD Search
		 * @description Enables search
		 */
		if (plugins.search.length || plugins.searchResults) {
			var handler = "bat/rd-search.php";
			var defaultTemplate = '<h6 class="search-title"><a target="_top" href="#{href}" class="search-link">#{title}</a></h6>' +
					'<p>...#{token}...</p>' +
					'<p class="match"><em>Terms matched: #{count} - URL: #{href}</em></p>';
			var defaultFilter = '*.html';

			if (plugins.search.length) {
				for (var i = 0; i < plugins.search.length; i++) {
					var searchItem = $(plugins.search[i]),
							options = {
								element:   searchItem,
								filter:    (searchItem.attr('data-search-filter')) ? searchItem.attr('data-search-filter') : defaultFilter,
								template:  (searchItem.attr('data-search-template')) ? searchItem.attr('data-search-template') : defaultTemplate,
								live:      (searchItem.attr('data-search-live')) ? searchItem.attr('data-search-live') : false,
								liveCount: (searchItem.attr('data-search-live-count')) ? parseInt(searchItem.attr('data-search-live'), 10) : 4,
								current:   0,
								processed: 0,
								timer:     {}
							};

					var $toggle = $('.rd-navbar-search-toggle');
					if ($toggle.length) {
						$toggle.on('click', (function (searchItem) {
							return function () {
								if (!($(this).hasClass('active'))) {
									searchItem.find('input').val('').trigger('propertychange');
								}
							}
						})(searchItem));
					}

					if (options.live) {
						var clearHandler = false;

						searchItem.find('input').on("input propertychange", $.proxy(function () {
							this.term = this.element.find('input').val().trim();
							this.spin = this.element.find('.input-group-addon');

							clearTimeout(this.timer);

							if (this.term.length > 2) {
								this.timer = setTimeout(liveSearch(this), 200);

								if (clearHandler === false) {
									clearHandler = true;

									$body.on("click", function (e) {
										if ($(e.toElement).parents('.rd-search').length === 0) {
											$('#rd-search-results-live').addClass('cleared').html('');
										}
									})
								}

							} else if (this.term.length === 0) {
								$('#' + this.live).addClass('cleared').html('');
							}
						}, options, this));
					}

					searchItem.submit($.proxy(function () {
						$('<input />').attr('type', 'hidden')
						.attr('name', "filter")
						.attr('value', this.filter)
						.appendTo(this.element);
						return true;
					}, options, this))
				}
			}

			if (plugins.searchResults.length) {
				var regExp = /\?.*s=([^&]+)\&filter=([^&]+)/g;
				var match = regExp.exec(location.search);

				if (match !== null) {
					$.get(handler, {
						s:        decodeURI(match[1]),
						dataType: "html",
						filter:   match[2],
						template: defaultTemplate,
						live:     ''
					}, function (data) {
						plugins.searchResults.html(data);
					})
				}
			}
		}

		/**
		 * ViewPort Universal
		 * @description Add class in viewport
		 */
		if (plugins.viewAnimate.length) {
			for (var i = 0; i < plugins.viewAnimate.length; i++) {
				var $view = $(plugins.viewAnimate[i]).not('.active');
				$document.on("scroll", $.proxy(function () {
					if (isScrolledIntoView(this)) {
						this.addClass("active");
					}
				}, $view))
				.trigger("scroll");
			}
		}

		/**
		 * Swiper
		 * @description  Enable Swiper Slider
		 */
		if (plugins.swiper.length) {

			for (let i = 0; i < plugins.swiper.length; i++) {

				let
					node = plugins.swiper[i],
					pag = $(node).find(".swiper-pagination"),
					params = parseJSON(node.getAttribute('data-swiper')),
					defaults = {
						speed:      1000,
						loop:       true,
						pagination: {
							el:        '.swiper-pagination',
							clickable: true,
						},
						navigation: {
							nextEl: '.swiper-button-next',
							prevEl: '.swiper-button-prev'
						},
						autoplay:   {
							delay: 5000
						}
					},
					xMode = {
						autoplay:      false,
						loop:          false,
						simulateTouch: false
					};

				defaults.pagination.renderBullet =  pag.length ? pag.attr("data-index-bullet") === "true" ? renderBullet : null : null;

				params.on = {
						init: function () {
							setBackgrounds(this);
							setHoverAutoplay(this);
							
							toggleSwiperCaptionAnimation(this);
							initLightGalleryItem($(node).find('[data-lightgallery="item"]'), 'lightGallery-in-carousel');

							// Real Previous Index must be set recent
							this.on('slideChangeTransitionEnd', function () {
								toggleSwiperCaptionAnimation(this);
							});
						}
				};

				new Swiper( node, Util.merge( isNoviBuilder ? [ defaults, params, xMode ] : [ defaults, params ] ) );
			}
		}

		/**
		 * WOW
		 * @description Enables Wow animation plugin
		 */
		if ($html.hasClass("wow-animation") && plugins.wow.length && !isNoviBuilder && isDesktop) {
			new WOW().init();
		}

		// RD Input Label
		if (plugins.rdInputLabel.length) {
			plugins.rdInputLabel.RDInputLabel();
		}

		// Regula
		if (plugins.regula.length) {
			attachFormValidator(plugins.regula);
		}

		// MailChimp Ajax subscription
		if (plugins.mailchimp.length) {
			for (i = 0; i < plugins.mailchimp.length; i++) {
				var $mailchimpItem = $(plugins.mailchimp[i]),
						$email = $mailchimpItem.find('input[type="email"]');

				// Required by MailChimp
				$mailchimpItem.attr('novalidate', 'true');
				$email.attr('name', 'EMAIL');

				$mailchimpItem.on('submit', $.proxy(function ($email, event) {
					event.preventDefault();

					var $this = this;

					var data = {},
							url = $this.attr('action').replace('/post?', '/post-json?').concat('&c=?'),
							dataArray = $this.serializeArray(),
							$output = $("#" + $this.attr("data-form-output"));

					for (i = 0; i < dataArray.length; i++) {
						data[dataArray[i].name] = dataArray[i].value;
					}

					$.ajax({
						data:       data,
						url:        url,
						dataType:   'jsonp',
						error:      function (resp, text) {
							$output.html('Server error: ' + text);

							setTimeout(function () {
								$output.removeClass("active");
							}, 4000);
						},
						success:    function (resp) {
							$output.html(resp.msg).addClass('active');
							$email[0].value = '';
							var $label = $('[for="' + $email.attr('id') + '"]');
							if ($label.length) $label.removeClass('focus not-empty');

							setTimeout(function () {
								$output.removeClass("active");
							}, 6000);
						},
						beforeSend: function (data) {
							var isNoviBuilder = window.xMode;

							var isValidated = (function () {
								var results, errors = 0;
								var elements = $this.find('[data-constraints]');
								var captcha = null;
								if (elements.length) {
									for (var j = 0; j < elements.length; j++) {

										var $input = $(elements[j]);
										if ((results = $input.regula('validate')).length) {
											for (var k = 0; k < results.length; k++) {
												errors++;
												$input.siblings(".form-validation").text(results[k].message).parent().addClass("has-error");
											}
										} else {
											$input.siblings(".form-validation").text("").parent().removeClass("has-error")
										}
									}

									if (captcha) {
										if (captcha.length) {
											return validateReCaptcha(captcha) && errors === 0
										}
									}

									return errors === 0;
								}
								return true;
							})();

							// Stop request if builder or inputs are invalide
							if (isNoviBuilder || !isValidated)
								return false;

							$output.html('Submitting...').addClass('active');
						}
					});

					return false;
				}, $mailchimpItem, $email));
			}
		}

		// Campaign Monitor ajax subscription
		if (plugins.campaignMonitor.length) {
			for (i = 0; i < plugins.campaignMonitor.length; i++) {
				var $campaignItem = $(plugins.campaignMonitor[i]);

				$campaignItem.on('submit', $.proxy(function (e) {
					var data = {},
							url = this.attr('action'),
							dataArray = this.serializeArray(),
							$output = $("#" + plugins.campaignMonitor.attr("data-form-output")),
							$this = $(this);

					for (i = 0; i < dataArray.length; i++) {
						data[dataArray[i].name] = dataArray[i].value;
					}

					$.ajax({
						data:       data,
						url:        url,
						dataType:   'jsonp',
						error:      function (resp, text) {
							$output.html('Server error: ' + text);

							setTimeout(function () {
								$output.removeClass("active");
							}, 4000);
						},
						success:    function (resp) {
							$output.html(resp.Message).addClass('active');

							setTimeout(function () {
								$output.removeClass("active");
							}, 6000);
						},
						beforeSend: function (data) {
							// Stop request if builder or inputs are invalide
							if (isNoviBuilder || !isValidated($this.find('[data-constraints]')))
								return false;

							$output.html('Submitting...').addClass('active');
						}
					});

					// Clear inputs after submit
					var inputs = $this[0].getElementsByTagName('input');
					for (var i = 0; i < inputs.length; i++) {
						inputs[i].value = '';
						var label = document.querySelector('[for="' + inputs[i].getAttribute('id') + '"]');
						if (label) label.classList.remove('focus', 'not-empty');
					}

					return false;
				}, $campaignItem));
			}
		}

		// RD Mailform
		if (plugins.rdMailForm.length) {
			var i, j, k,
					msg = {
						'MF000': 'Successfully sent!',
						'MF001': 'Recipients are not set!',
						'MF002': 'Form will not work locally!',
						'MF003': 'Please, define email field in your form!',
						'MF004': 'Please, define type of your form!',
						'MF254': 'Something went wrong with PHPMailer!',
						'MF255': 'Aw, snap! Something went wrong.'
					};

			for (i = 0; i < plugins.rdMailForm.length; i++) {
				var $form = $(plugins.rdMailForm[i]),
						formHasCaptcha = false;

				$form.attr('novalidate', 'novalidate').ajaxForm({
					data:         {
						"form-type": $form.attr("data-form-type") || "contact",
						"counter":   i
					},
					beforeSubmit: function (arr, $form, options) {
						if (isNoviBuilder)
							return;

						var form = $(plugins.rdMailForm[this.extraData.counter]),
								inputs = form.find("[data-constraints]"),
								output = $("#" + form.attr("data-form-output")),
								captcha = form.find('.recaptcha'),
								captchaFlag = true;

						output.removeClass("active error success");

						if (isValidated(inputs, captcha)) {

							// veify reCaptcha
							if (captcha.length) {
								var captchaToken = captcha.find('.g-recaptcha-response').val(),
										captchaMsg = {
											'CPT001': 'Please, setup you "site key" and "secret key" of reCaptcha',
											'CPT002': 'Something wrong with google reCaptcha'
										};

								formHasCaptcha = true;

								$.ajax({
									method: "POST",
									url:    "bat/reCaptcha.php",
									data:   {'g-recaptcha-response': captchaToken},
									async:  false
								})
								.done(function (responceCode) {
									if (responceCode !== 'CPT000') {
										if (output.hasClass("snackbars")) {
											output.html('<p><span class="icon text-middle mdi mdi-check icon-xxs"></span><span>' + captchaMsg[responceCode] + '</span></p>')

											setTimeout(function () {
												output.removeClass("active");
											}, 3500);

											captchaFlag = false;
										} else {
											output.html(captchaMsg[responceCode]);
										}

										output.addClass("active");
									}
								});
							}

							if (!captchaFlag) {
								return false;
							}

							form.addClass('form-in-process');

							if (output.hasClass("snackbars")) {
								output.html('<p><span class="icon text-middle fa fa-circle-o-notch fa-spin icon-xxs"></span><span>Sending</span></p>');
								output.addClass("active");
							}
						} else {
							return false;
						}
					},
					error:        function (result) {
						if (isNoviBuilder)
							return;

						var output = $("#" + $(plugins.rdMailForm[this.extraData.counter]).attr("data-form-output")),
								form = $(plugins.rdMailForm[this.extraData.counter]);

						output.text(msg[result]);
						form.removeClass('form-in-process');

						if (formHasCaptcha) {
							grecaptcha.reset();
						}
					},
					success:      function (result) {
						if (isNoviBuilder)
							return;

						var form = $(plugins.rdMailForm[this.extraData.counter]),
								output = $("#" + form.attr("data-form-output")),
								select = form.find('select');

						form
						.addClass('success')
						.removeClass('form-in-process');

						if (formHasCaptcha) {
							grecaptcha.reset();
						}

						result = result.length === 5 ? result : 'MF255';
						output.text(msg[result]);

						if (result === "MF000") {
							if (output.hasClass("snackbars")) {
								output.html('<p><span class="icon text-middle mdi mdi-check icon-xxs"></span><span>' + msg[result] + '</span></p>');
							} else {
								output.addClass("active success");
							}
						} else {
							if (output.hasClass("snackbars")) {
								output.html(' <p class="snackbars-left"><span class="icon icon-xxs mdi mdi-alert-outline text-middle"></span><span>' + msg[result] + '</span></p>');
							} else {
								output.addClass("active error");
							}
						}

						form.clearForm();

						if (select.length) {
							select.val(null).trigger('change');
						}

						form.find('input, textarea').trigger('blur');

						setTimeout(function () {
							output.removeClass("active error success");
							form.removeClass('success');
						}, 3500);
					}
				});
			}
		}

		/**
		 * Stepper
		 * @description Enables Stepper Plugin
		 */
		if (plugins.stepper.length) {
			plugins.stepper.stepper({
				labels: {
					up:   "",
					down: ""
				}
			});
		}

		/**
		 * Custom Toggles
		 */
		if (plugins.customToggle.length) {
			for (var i = 0; i < plugins.customToggle.length; i++) {
				var $this = $(plugins.customToggle[i]);

				$this.on('click', $.proxy(function (event) {
					event.preventDefault();

					var $ctx = $(this);
					$($ctx.attr('data-custom-toggle')).add(this).toggleClass('active');
				}, $this));

				if ($this.attr("data-custom-toggle-hide-on-blur") === "true") {
					$body.on("click", $this, function (e) {
						if (e.target !== e.data[0]
								&& $(e.data.attr('data-custom-toggle')).find($(e.target)).length
								&& e.data.find($(e.target)).length === 0) {
							$(e.data.attr('data-custom-toggle')).add(e.data[0]).removeClass('active');
						}
					})
				}

				if ($this.attr("data-custom-toggle-disable-on-blur") === "true") {
					$body.on("click", $this, function (e) {
						if (e.target !== e.data[0]
								&& $(e.data.attr('data-custom-toggle')).find($(e.target)).length === 0
								&& e.data.find($(e.target)).length === 0) {
							$(e.data.attr('data-custom-toggle')).add(e.data[0]).removeClass('active');
						}
					})
				}
			}
		}

		/**
		 * Material Parallax
		 * @description Enables Material Parallax plugin
		 */
		if (plugins.materialParallax.length) {
			if (!isNoviBuilder && !isIE && !isMobile) {
				plugins.materialParallax.parallax();

				// heavy pages fix
				$window.on('load', function () {
					setTimeout(function () {
						$window.scroll();
					}, 500);
				});
			} else {
				for (var i = 0; i < plugins.materialParallax.length; i++) {
					var parallax = $(plugins.materialParallax[i]),
							imgPath = parallax.data("parallax-img");

					parallax.css({
						"background-image": 'url(' + imgPath + ')',
						"background-size":  "cover"
					});
				}
			}
		}

		/**
		 * RD Range
		 * @description Enables RD Range plugin
		 */
		if (plugins.rdRange.length && !isNoviBuilder) {
			plugins.rdRange.RDRange({});
		}

		if (plugins.inlineToggle.length) {
			for (var i = 0; i < plugins.inlineToggle.length; i++) {
				var $element = $(plugins.inlineToggle[i]);

				$element.on('click', (function ($element) {
					return function (event) {
						event.preventDefault();
						$element.parents(".inline-toggle-parent").toggleClass("active");
					}
				})($element));

				$body.on('click', $element, (function ($element) {
					return function (event) {
						if (event.target !== event.data[0]
								&& !$(event.target).hasClass("inline-toggle-parent")
								&& event.data.find($(event.target)).length === 0) {

							$element.parents(".inline-toggle-parent").removeClass("active");
						}
					}
				})($element));
			}
		}

		/**
		 * Focus toggle
		 */
		if (plugins.focusToggle.length) {
			for (var i = 0; i < plugins.focusToggle.length; i++) {
				var $element = $(plugins.focusToggle[i]);

				$element.hover(
						function (event) {
							event.preventDefault();
							$(this).parents('.focus-toggle-parent').addClass('focus');
						}
				);

				$element.parents('.focus-toggle-parent').hover(
						function () {
						},
						function () {
							$(this).removeClass('focus');
						}
				)
			}
		}

		// Radio Panel
		if (plugins.radioPanel) {
			for (var i = 0; i < plugins.radioPanel.length; i++) {
				var $element = $(plugins.radioPanel[i]);
				$element.on('click', function () {
					plugins.radioPanel.removeClass('active');
					$(this).addClass('active');
				})
			}
		}

		// Slick carousel
		if (plugins.slick.length) {
			for (var i = 0; i < plugins.slick.length; i++) {
				var $slickItem = $(plugins.slick[i]);

				$slickItem.on('init', function (slick) {
					initLightGallery($(slick).find('[data-lightgallery="item"]'), 'lightGallery-in-carousel');
				});

				$slickItem.slick({
					slidesToScroll: parseInt($slickItem.attr('data-slide-to-scroll'), 10) || 1,
					asNavFor:       $slickItem.attr('data-for') || false,
					dots:           $slickItem.attr("data-dots") === "true",
					infinite:       isNoviBuilder ? false : $slickItem.attr("data-loop") === "true",
					focusOnSelect:  true,
					arrows:         $slickItem.attr("data-arrows") === "true",
					swipe:          $slickItem.attr("data-swipe") === "true",
					autoplay:       isNoviBuilder ? false : $slickItem.attr("data-autoplay") === "true",
					autoplaySpeed:  $slickItem.attr("data-autoplay-speed") ? parseInt($slickItem.attr("data-autoplay-speed")) : 3500,
					vertical:       $slickItem.attr("data-vertical") === "true",
					centerMode:     $slickItem.attr("data-center-mode") === "true",
					centerPadding:  $slickItem.attr("data-center-padding") ? $slickItem.attr("data-center-padding") : '0.50',
					mobileFirst:    true,
					rtl:            isRtl,
					responsive:     [
						{
							breakpoint: 0,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-items'), 10) || 1
							}
						},
						{
							breakpoint: 575,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-sm-items'), 10) || 1
							}
						},
						{
							breakpoint: 767,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-md-items'), 10) || 1
							}
						},
						{
							breakpoint: 991,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-lg-items'), 10) || 1
							}
						},
						{
							breakpoint: 1199,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-xl-items'), 10) || 1
							}
						}
					]
				})
				.on('afterChange', function (event, slick, currentSlide, nextSlide) {
					var $this = $(this),
							childCarousel = $this.attr('data-child');

					if (childCarousel) {
						$(childCarousel + ' .slick-slide').removeClass('slick-current');
						$(childCarousel + ' .slick-slide').eq(currentSlide).addClass('slick-current');
					}
				});
			}
		}

		// Video Overlay
		if (plugins.videoOverlay.length) {
			for (var i = 0; i < plugins.videoOverlay.length; i++) {
				var overlay = plugins.videoOverlay[i];
				if (overlay) {
					overlay.style.opacity = '1';
					overlay.addEventListener('click', function (e) {
						$(this).animate({
							opacity: 0
						}, function () {
							this.style.display = 'none';
						});
					});
				}
			}
		}

		// Lazy iframe
		if (plugins.lazyIFrame.length) {
			for (let i = 0; i < plugins.lazyIFrame.length; i++) {
				lazyInit( $(plugins.lazyIFrame[i]), function () {
					this.attr('src', this.attr('data-src'));
				});
			}
		}
		// ThemeSwitcher
		if (plugins.themeSwitcher.length) {
			document.documentElement.addEventListener('theme-switching', function () {
				if (windowReady) {
					loaderTimeoutId = setTimeout(function () {
						plugins.preloader.removeClass("loaded");
					}, 500);
				}
			});

			document.documentElement.addEventListener('theme-switched', function (event) {
				if (windowReady) {
					clearTimeout(loaderTimeoutId);
					setTimeout(function () {
						if (windowReady) {
							plugins.preloader.addClass("loaded");
						}
					}, 400);
				}
			});

			document.documentElement.addEventListener('theme-color-change', function (event) {
				document.querySelector(event.switcher.selectors.color + '[name="' + event.variable + '"]').style.backgroundColor = event.value;
			});

			var switcher = themeSwitcherInit({
				variablesFallback: isIE,
				cookie:            false,
				themes:            {
					"soccer":     {
						styles: 'css/style.css'
					},
					"baseball":   {
						styles: 'css/style-baseball.css'
					},
					"basketball": {
						styles: 'css/style-basketball.css'
					},
					"billiards":  {
						styles: 'css/style-billiards.css'
					},
					"bowling":    {
						styles: 'css/style-bowling.css'
					},
					"rugby":      {
						styles: 'css/style-rugby.css'
					},
					"tennis":      {
						styles: 'css/style-tennis.css'
					}
				}
			});
		}
	});
}());

"use strict";
(function () {

	/**
	 * Global variables
	 */
	let
		userAgent = navigator.userAgent.toLowerCase(),
		isIE = userAgent.indexOf("msie") !== -1 ? parseInt(userAgent.split("msie")[1], 10) : userAgent.indexOf("trident") !== -1 ? 11 : userAgent.indexOf("edge") !== -1 ? 12 : false;

	// Unsupported browsers
	if (isIE !== false && isIE < 12) {
		console.warn("[Core] detected IE" + isIE + ", load alert");
		var script = document.createElement("script");
		script.src = "./js/support.js";
		document.querySelector("head").appendChild(script);
	}

	let
			initialDate = new Date(),

			$document = $(document),
			$window = $(window),
			$html = $("html"),
			$body = $("body"),

			isDesktop = $html.hasClass("desktop"),
			isRtl = $html.attr("dir") === "rtl",
			isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),

			windowReady = false,
			isNoviBuilder = false,
			loaderTimeoutId,

			plugins = {
				preloader:               $(".preloader"),
				bootstrapTooltip:        $("[data-bs-toggle='tooltip']"),
				bootstrapModalDialog:    $('.modal'),
				bootstrapTabs:           $(".tabs-custom"),
				bootstrapCollapse:       $(".card-custom"),
				rdNavbar:                $(".rd-navbar"),
				materialParallax:        $(".parallax-container"),
				rdMailForm:              $(".rd-mailform"),
				rdInputLabel:            $(".form-label"),
				regula:                  $("[data-constraints]"),
				rdRange:                 $('.rd-range'),
				wow:                     $(".wow"),
				owl:                     $(".owl-carousel"),
				swiper:                  $(".swiper-slider"),
				search:                  $(".rd-search"),
				searchResults:           $('.rd-search-results'),
				statefulButton:          $('.btn-stateful'),
				popover:                 $('[data-bs-toggle="popover"]'),
				viewAnimate:             $('.view-animate'),
				radio:                   $("input[type='radio']"),
				checkbox:                $("input[type='checkbox']"),
				customToggle:            $("[data-custom-toggle]"),
				captcha:                 $('.recaptcha'),
				lightGallery:            $("[data-lightgallery='group']"),
				lightGalleryItem:        $("[data-lightgallery='item']"),
				lightDynamicGalleryItem: $("[data-lightgallery='dynamic']"),
				mailchimp:               $('.mailchimp-mailform'),
				campaignMonitor:         $('.campaign-mailform'),
				copyrightYear:           $(".copyright-year"),
				selectFilter:            $("select"),
				inlineToggle:            $('.inline-toggle'),
				focusToggle:             $('.focus-toggle'),
				stepper:                 $("input[type='number']"),
				radioPanel:              $('.radio-panel .radio-inline'),
				slick:                   $('.slick-slider'),
				videoOverlay:            $('.video-overlay'),
				counter:                 document.querySelectorAll('.counter'),
				progressLinear:          document.querySelectorAll('.progress-linear'),
				progressCircle:          document.querySelectorAll('.progress-bar-js'),
				countdownCircle:         document.querySelectorAll('.countdown-circle-container'),
				countdown:               document.querySelectorAll('.countdown'),
				doughnutChart:           document.querySelectorAll('.doughnut-chart'),
				lazyIFrame:              $('iframe[data-src]'),
				themeSwitcher:           $("[data-theme-name]")
			};

	/**
	 * @desc Check the element was been scrolled into the view
	 * @param {object} elem - jQuery object
	 * @return {boolean}
	 */
	function isScrolledIntoView(elem) {
		if (isNoviBuilder) return true;
		return elem.offset().top + elem.outerHeight() >= $window.scrollTop() && elem.offset().top <= $window.scrollTop() + $window.height();
	}

	/**
	 * @desc Calls a function when element has been scrolled into the view
	 * @param {object} element - jQuery object
	 * @param {function} func - init function
	 */
	function lazyInit(element, func) {
		var scrollHandler = function () {
			if ((!element.hasClass('lazy-loaded') && (isScrolledIntoView(element)))) {
				func.call(element);
				element.addClass('lazy-loaded');
			}
		};

		scrollHandler();
		$window.on('scroll', scrollHandler);
	}

	// Initialize scripts that require a loaded window
	$window.on('load', function () {
		// Page loader & Page transition
		if (plugins.preloader.length && !isNoviBuilder) {
			pageTransition({
				target:            document.querySelector('.page'),
				delay:             0,
				duration:          500,
				classIn:           'fadeIn',
				classOut:          'fadeOut',
				classActive:       'animated',
				conditions:        function (event, link) {
					return link && !/(\#|javascript:void\(0\)|callto:|tel:|mailto:|:\/\/)/.test(link) && !event.currentTarget.hasAttribute('data-lightgallery');
				},
				onTransitionStart: function (options) {
					setTimeout(function () {
						plugins.preloader.removeClass('loaded');
					}, options.duration * .75);
				},
				onReady:           function () {
					plugins.preloader.addClass('loaded');
					windowReady = true;
				}
			});
		}

		// Countdown
		if (plugins.countdownCircle.length) {

			for (let i = 0; i < plugins.countdownCircle.length; i++) {
				let
						node = plugins.countdownCircle[i],
						countdown = aCountdown({
							node:  node,
							from:  node.getAttribute('data-from'),
							to:    node.getAttribute('data-to'),
							count: node.getAttribute('data-count'),
							tick:  100,
						});
			}
		}

		// jQuery Countdown
		if (plugins.countdown.length) {
			for (let i = 0; i < plugins.countdown.length; i++) {
				var $countDownItem = $(plugins.countdown[i]),
						d = new Date(),
						type = $countDownItem.attr('data-type'),
						time = $countDownItem.attr('data-time'),
						format = $countDownItem.attr('data-format'),
						settings = [];

				// Classic style
				if ($countDownItem.attr('data-style') === 'short') {
					settings['labels'] = ['Yeas', 'Mons', 'Weks', 'Days', 'Hrs', 'Mins', 'Secs'];
				}

				d.setTime(Date.parse(time)).toLocaleString();
				settings[type] = d;
				settings['format'] = format;
				$countDownItem.countdown(settings);
			}
		}

		// Progress Bar
		if (plugins.progressLinear) {
			for (let i = 0; i < plugins.progressLinear.length; i++) {
				let
						container = plugins.progressLinear[i],
						bar = container.querySelector('.progress-bar-linear'),
						duration = container.getAttribute('data-duration') || 1000,
						counter = aCounter({
							node:     container.querySelector('.progress-value'),
							duration: duration,
							onStart:  function () {
								this.custom.bar.style.width = this.params.to + '%';
							}
						});

				bar.style.transitionDuration = duration / 1000 + 's';
				counter.custom = {
					container: container,
					bar:       bar,
					onScroll:  (function () {
						if ((Util.inViewport(this.custom.container) && !this.custom.container.classList.contains('animated')) || isNoviBuilder) {
							this.run();
							this.custom.container.classList.add('animated');
						}
					}).bind(counter),
					onBlur:    (function () {
						this.params.to = parseInt(this.params.node.textContent, 10);
						this.run();
					}).bind(counter)
				};

				if (isNoviBuilder) {
					counter.run();
					counter.params.node.addEventListener('blur', counter.custom.onBlur);
				} else {
					counter.custom.onScroll();
					document.addEventListener('scroll', counter.custom.onScroll);
				}
			}
		}

		// Progress Circle
		if (plugins.progressCircle) {
			for (let i = 0; i < plugins.progressCircle.length; i++) {
				let
						container = plugins.progressCircle[i],
						counter = aCounter({
							node:     container.querySelector('.progress-circle-counter'),
							duration: 500,
							onUpdate: function (value) {
								this.custom.bar.render(value * 3.6);
							}
						});

				counter.params.onComplete = counter.params.onUpdate;

				counter.custom = {
					container: container,
					bar:       aProgressCircle({node: container.querySelector('.progress-circle-bar')}),
					onScroll:  (function () {
						if (Util.inViewport(this.custom.container) && !this.custom.container.classList.contains('animated')) {
							this.run();
							this.custom.container.classList.add('animated');
						}
					}).bind(counter),
					onBlur:    (function () {
						this.params.to = parseInt(this.params.node.textContent, 10);
						this.run();
					}).bind(counter)
				};

				if (isNoviBuilder) {
					counter.run();
					counter.params.node.addEventListener('blur', counter.custom.onBlur);
				} else {
					counter.custom.onScroll();
					window.addEventListener('scroll', counter.custom.onScroll);
				}
			}
		}

		// doughnutChart
		if (plugins.doughnutChart.length) {
			for (var i = 0; i < plugins.doughnutChart.length; i++) {
				var $element = $(plugins.doughnutChart[i]),
						$chartDataList = $element.find('.doughnut-chart-list').first();

				if ($chartDataList) {
					var ratioValues = [],
							chartDataListItems = $chartDataList.find('> li');

					for (var k = 0; k < chartDataListItems.length; k++) {
						var value = Math.abs(chartDataListItems[k].getAttribute('data-value') * 1);
						ratioValues.push({});
						ratioValues[k].title = chartDataListItems[k].innerHTML;
						ratioValues[k].value = value ? value : 10;
					}

					$element.drawDoughnutChart(
							ratioValues, {
								segmentShowStroke:     false,
								segmentStrokeWidth:    0,
								baseColor:             '#f5f5f5',
								baseOffset:            0,
								edgeOffset:            1,
								percentageInnerCutout: 85
							}
					);
				}
			}
		}
	});

	// Initialize All Scripts
	$(function () {
		isNoviBuilder = window.xMode;

		/**
		 * Wrapper to eliminate json errors
		 * @param {string} str - JSON string
		 * @returns {object} - parsed or empty object
		 */
		function parseJSON ( str ) {
			try {
				if ( str )  return JSON.parse( str );
				else return {};
			} catch ( error ) {
				console.warn( error );
				return {};
			}
		}

		/**
		 * @desc Sets slides background images from attribute 'data-slide-bg'
		 * @param {object} swiper - swiper instance
		 */
		function setBackgrounds(swiper) {
			let swipersBg = swiper.el.querySelectorAll('[data-slide-bg]');

			for (let i = 0; i < swipersBg.length; i++) {
				let swiperBg = swipersBg[i];
				swiperBg.style.backgroundImage = 'url(' + swiperBg.getAttribute('data-slide-bg') + ')';
				swiperBg.style.backgroundSize = 'cover';
			}
		}

		/**
		 * @desc Sets hover autoplay  from attribute 'data-autoplay-hover'
		 * @param {object} swiper - swiper instance
		 */
		function setHoverAutoplay(swiper) {
			if (swiper.el.getAttribute('data-autoplay-hover') === 'true') {
				let hoverEvent;
				swiper.el.addEventListener('mouseenter', function (e) {
					hoverEvent = setInterval(function () {
						swiper.slideNext();
					}, +swiper.el.getAttribute('data-autoplay-hover-delay') );
				});
				swiper.el.addEventListener('mouseleave', function (e) {
					clearInterval(hoverEvent);
				})
			}
		}
		
		/**
		 * toggleSwiperCaptionAnimation
		 * @description  toggle swiper animations on active slides
		 */
		function toggleSwiperCaptionAnimation(swiper) {
			let prevSlide = $(swiper.$el[0]),
				nextSlide = $(swiper.slides[swiper.activeIndex]);
			
			prevSlide
				.find("[data-caption-animate]")
				.each(function () {
					let $this = $(this);
					$this
						.removeClass("animated")
						.removeClass($this.attr("data-caption-animate"))
						.addClass("not-animated");
				});
			
			nextSlide
				.find("[data-caption-animate]")
				.each(function () {
					let $this = $(this),
						delay = $this.attr("data-caption-delay");
					
					
					if (!isNoviBuilder) {
						setTimeout(function () {
							$this
								.removeClass("not-animated")
								.addClass($this.attr("data-caption-animate"))
								.addClass("animated");
						}, delay ? parseInt(delay) : 0);
					} else {
						$this
							.removeClass("not-animated")
					}
				});
		}

		/**
		 * @desc Swiper Render Bullet function
		 */
		function renderBullet(index, className) {
			return '<span class="' + className + '"><span>' + ((index + 1) < 10 ? ('0' + (index + 1)) : (index + 1)) + '</span></span>';
		}

		/**
		 * initOwlCarousel
		 * @description  Init owl carousel plugin
		 */
		function initOwlCarousel(c) {
			var aliaces = ["-", "-sm-", "-md-", "-lg-", "-xl-", "-xxl-"],
					values = [0, 576, 768, 992, 1200, 1600],
					responsive = {};

			for (var j = 0; j < values.length; j++) {
				responsive[values[j]] = {};
				for (var k = j; k >= -1; k--) {
					if (!responsive[values[j]]["items"] && c.attr("data" + aliaces[k] + "items")) {
						responsive[values[j]]["items"] = k < 0 ? 1 : parseInt(c.attr("data" + aliaces[k] + "items"), 10);
					}
					if (!responsive[values[j]]["stagePadding"] && responsive[values[j]]["stagePadding"] !== 0 && c.attr("data" + aliaces[k] + "stage-padding")) {
						responsive[values[j]]["stagePadding"] = k < 0 ? 0 : parseInt(c.attr("data" + aliaces[k] + "stage-padding"), 10);
					}
					if (!responsive[values[j]]["margin"] && responsive[values[j]]["margin"] !== 0 && c.attr("data" + aliaces[k] + "margin")) {
						responsive[values[j]]["margin"] = k < 0 ? 30 : parseInt(c.attr("data" + aliaces[k] + "margin"), 10);
					}
				}
			}

			c.on("initialized.owl.carousel", function (event) {
				var $carousel = $(event.currentTarget);
				// Enable custom pagination
				if (c.attr('data-dots-custom')) {
					var customPag = $($carousel.attr("data-dots-custom")),
							active = 0;

					if ($carousel.attr('data-active')) {
						active = parseInt($carousel.attr('data-active'), 10);
					}

					$carousel.trigger('to.owl.carousel', [active, 300, true]);
					customPag.find("[data-owl-item='" + active + "']").addClass("active");

					customPag.find("[data-owl-item]").on('click', function (e) {
						e.preventDefault();
						$carousel.trigger('to.owl.carousel', [parseInt(this.getAttribute("data-owl-item"), 10), 300, true]);
					});

					$carousel.on("translate.owl.carousel", function (event) {
						customPag.find(".active").removeClass("active");
						customPag.find("[data-owl-item='" + event.item.index + "']").addClass("active")
					});
				}

				// Enable Custom Navigation
				if (c.attr('data-nav-custom')) {
					var customNav = $carousel.parents($carousel.attr("data-nav-custom"));
					// Custom Navigation Events
					customNav.find(".owl-arrow-next").click(function (e) {
						e.preventDefault();
						$carousel.trigger('next.owl.carousel');
					});
					customNav.find(".owl-arrow-prev").click(function (e) {
						e.preventDefault();
						$carousel.trigger('prev.owl.carousel');
					});
				}
			});

			c.on("initialized.owl.carousel", function (event) {
				initLightGalleryItem(c.find('[data-lightgallery="item"]'), 'lightGallery-in-carousel');
			});

			c.owlCarousel({
				autoplay:           isNoviBuilder ? false : c.attr("data-autoplay") === "true",
				autoplayTimeout:    c.data("autoplay-timeout"),
				autoplayHoverPause: c.data("autoplay-hover-pause"),
				autoplaySpeed:      c.data("data-autoplay-speed"),
				center:             c.data("data-center"),
				startPosition:      c.data("data-start-position"),
				loop:               isNoviBuilder ? false : c.attr("data-loop") === "true",
				items:              1,
				rtl:                isRtl,
				autoWidth:          c.data("data-autowidth") === "true",
				autoHeight:         c.data("data-autoheight") === "true",
				dotsContainer:      c.attr("data-pagination-class") || false,
				navContainer:       c.attr("data-navigation-class") || false,
				mouseDrag:          isNoviBuilder ? false : c.attr("data-mouse-drag") !== "false",
				touchDrag:          c.data("data-touch-drag"),
				dragEndSpeed:       c.data("data-drag-end-speed"),
				nav:                c.attr('data-nav') === 'true',
				navSpeed:           c.data("data-nav-speed"),
				dots:               c.attr("data-dots") === "true",
				dotsSpeed:          c.data("dots-speed"),
				dotsEach:           c.attr("data-dots-each") ? parseInt(c.attr("data-dots-each"), 10) : false,
				animateIn:          c.attr('data-animation-in') ? c.attr('data-animation-in') : false,
				animateOut:         c.attr('data-animation-out') ? c.attr('data-animation-out') : false,
				lazyLoad:           c.data("data-lazy-load"),
				responsive:         responsive,
				navText:            function () {
					try {
						return JSON.parse(c.attr("data-nav-text"));
					} catch (e) {
						return [];
					}
				}(),
				navClass:           function () {
					try {
						return JSON.parse(c.attr("data-nav-class"));
					} catch (e) {
						return ['owl-prev', 'owl-next'];
					}
				}()
			});

			// Mousewheel plugin
			var owl = $('.owl-mousewheel');
			owl.on('mousewheel', '.owl-stage', function (e) {
				var curr = $(this);
				if (e.deltaY > 0) {
					curr.trigger('prev.owl', [800]);
				} else {
					curr.trigger('next.owl', [800]);
				}
				e.preventDefault();
				e.stopImmediatePropagation();
			});

		}

		/**
		 * @desc Initialize the gallery with set of images
		 * @param {object} itemsToInit - jQuery object
		 * @param {string} [addClass] - additional gallery class
		 */
		function initLightGallery(itemsToInit, addClass) {
			if (!isNoviBuilder) {
				$(itemsToInit).lightGallery({
					thumbnail: $(itemsToInit).attr("data-lg-thumbnail") !== "false",
					selector:  "[data-lightgallery='item']",
					autoplay:  $(itemsToInit).attr("data-lg-autoplay") === "true",
					pause:     parseInt($(itemsToInit).attr("data-lg-autoplay-delay")) || 5000,
					addClass:  addClass,
					mode:      $(itemsToInit).attr("data-lg-animation") || "lg-slide",
					loop:      $(itemsToInit).attr("data-lg-loop") !== "false"
				});
			}
		}

		/**
		 * @desc Initialize the gallery with dynamic addition of images
		 * @param {object} itemsToInit - jQuery object
		 * @param {string} [addClass] - additional gallery class
		 */
		function initDynamicLightGallery(itemsToInit, addClass) {
			if (!isNoviBuilder) {
				$(itemsToInit).on("click", function () {
					$(itemsToInit).lightGallery({
						thumbnail: $(itemsToInit).attr("data-lg-thumbnail") !== "false",
						selector:  "[data-lightgallery='item']",
						autoplay:  $(itemsToInit).attr("data-lg-autoplay") === "true",
						pause:     parseInt($(itemsToInit).attr("data-lg-autoplay-delay")) || 5000,
						addClass:  addClass,
						mode:      $(itemsToInit).attr("data-lg-animation") || "lg-slide",
						loop:      $(itemsToInit).attr("data-lg-loop") !== "false",
						dynamic:   true,
						dynamicEl: JSON.parse($(itemsToInit).attr("data-lg-dynamic-elements")) || []
					});
				});
			}
		}

		/**
		 * @desc Initialize the gallery with one image
		 * @param {object} itemToInit - jQuery object
		 * @param {string} [addClass] - additional gallery class
		 */
		function initLightGalleryItem(itemToInit, addClass) {
			if (!isNoviBuilder) {
				$(itemToInit).lightGallery({
					selector:            "this",
					addClass:            addClass,
					counter:             false,
					youtubePlayerParams: {
						modestbranding: 1,
						showinfo:       0,
						rel:            0,
						controls:       0
					},
					vimeoPlayerParams:   {
						byline:   0,
						portrait: 0
					}
				});
			}
		}

		/**
		 * Live Search
		 * @description  create live search results
		 */
		function liveSearch(options) {
			$('#' + options.live).removeClass('cleared').html();
			options.current++;
			options.spin.addClass('loading');
			$.get(handler, {
				s:          decodeURI(options.term),
				liveSearch: options.live,
				dataType:   "html",
				liveCount:  options.liveCount,
				filter:     options.filter,
				template:   options.template
			}, function (data) {
				options.processed++;
				var live = $('#' + options.live);
				if ((options.processed === options.current) && !live.hasClass('cleared')) {
					live.find('> #search-results').removeClass('active');
					live.html(data);
					setTimeout(function () {
						live.find('> #search-results').addClass('active');
					}, 50);
				}
				options.spin.parents('.rd-search').find('.input-group-addon').removeClass('loading');
			})
		}

		/**
		 * @desc Attach form validation to elements
		 * @param {object} elements - jQuery object
		 */
		function attachFormValidator(elements) {
			// Custom validator - phone number
			regula.custom({
				name:           'PhoneNumber',
				defaultMessage: 'Invalid phone number format',
				validator:      function () {
					if (this.value === '') return true;
					else return /^(\+\d)?[0-9\-\(\) ]{5,}$/i.test(this.value);
				}
			});

			for (var i = 0; i < elements.length; i++) {
				var o = $(elements[i]), v;
				o.addClass("form-control-has-validation").after("<span class='form-validation'></span>");
				v = o.parent().find(".form-validation");
				if (v.is(":last-child")) o.addClass("form-control-last-child");
			}

			elements.on('input change propertychange blur', function (e) {
				var $this = $(this), results;

				if (e.type !== "blur") if (!$this.parent().hasClass("has-error")) return;
				if ($this.parents('.rd-mailform').hasClass('success')) return;

				if ((results = $this.regula('validate')).length) {
					for (i = 0; i < results.length; i++) {
						$this.siblings(".form-validation").text(results[i].message).parent().addClass("has-error");
					}
				} else {
					$this.siblings(".form-validation").text("").parent().removeClass("has-error")
				}
			}).regula('bind');

			var regularConstraintsMessages = [
				{
					type:       regula.Constraint.Required,
					newMessage: "The text field is required."
				},
				{
					type:       regula.Constraint.Email,
					newMessage: "The email is not a valid email."
				},
				{
					type:       regula.Constraint.Numeric,
					newMessage: "Only numbers are required"
				},
				{
					type:       regula.Constraint.Selected,
					newMessage: "Please choose an option."
				}
			];

			for (var i = 0; i < regularConstraintsMessages.length; i++) {
				var regularConstraint = regularConstraintsMessages[i];

				regula.override({
					constraintType: regularConstraint.type,
					defaultMessage: regularConstraint.newMessage
				});
			}
		}

		/**
		 * @desc Check if all elements pass validation
		 * @param {object} elements - object of items for validation
		 * @param {object} captcha - captcha object for validation
		 * @return {boolean}
		 */
		function isValidated(elements, captcha) {
			var results, errors = 0;

			if (elements.length) {
				for (var j = 0; j < elements.length; j++) {

					var $input = $(elements[j]);
					if ((results = $input.regula('validate')).length) {
						for (k = 0; k < results.length; k++) {
							errors++;
							$input.siblings(".form-validation").text(results[k].message).parent().addClass("has-error");
						}
					} else {
						$input.siblings(".form-validation").text("").parent().removeClass("has-error")
					}
				}

				if (captcha) {
					if (captcha.length) {
						return validateReCaptcha(captcha) && errors === 0
					}
				}

				return errors === 0;
			}
			return true;
		}

		/**
		 * @desc Validate google reCaptcha
		 * @param {object} captcha - captcha object for validation
		 * @return {boolean}
		 */
		function validateReCaptcha(captcha) {
			var captchaToken = captcha.find('.g-recaptcha-response').val();

			if (captchaToken.length === 0) {
				captcha
				.siblings('.form-validation')
				.html('Please, prove that you are not robot.')
				.addClass('active');
				captcha
				.closest('.form-wrap')
				.addClass('has-error');

				captcha.on('propertychange', function () {
					var $this = $(this),
							captchaToken = $this.find('.g-recaptcha-response').val();

					if (captchaToken.length > 0) {
						$this
						.closest('.form-wrap')
						.removeClass('has-error');
						$this
						.siblings('.form-validation')
						.removeClass('active')
						.html('');
						$this.off('propertychange');
					}
				});

				return false;
			}

			return true;
		}

		/**
		 * @desc Initialize Google reCaptcha
		 */
		window.onloadCaptchaCallback = function () {
			for (var i = 0; i < plugins.captcha.length; i++) {
				var $capthcaItem = $(plugins.captcha[i]);

				grecaptcha.render(
						$capthcaItem.attr('id'),
						{
							sitekey:  $capthcaItem.attr('data-sitekey'),
							size:     $capthcaItem.attr('data-size') ? $capthcaItem.attr('data-size') : 'normal',
							theme:    $capthcaItem.attr('data-theme') ? $capthcaItem.attr('data-theme') : 'light',
							callback: function (e) {
								$('.recaptcha').trigger('propertychange');
							}
						}
				);
				$capthcaItem.after("<span class='form-validation'></span>");
			}
		};

		/**
		 * Init Bootstrap tooltip
		 * @description  calls a function when need to init bootstrap tooltips
		 */
		function initBootstrapTooltip(tooltipPlacement) {
			if (window.innerWidth < 576) {
				plugins.bootstrapTooltip.tooltip('dispose');
				plugins.bootstrapTooltip.tooltip({
					placement: 'bottom'
				});
			} else {
				plugins.bootstrapTooltip.tooltip('dispose');
				plugins.bootstrapTooltip.tooltip({
					placement: tooltipPlacement
				});
			}
		}

		/**
		 * Google ReCaptcha
		 * @description Enables Google ReCaptcha
		 */
		if (plugins.captcha.length) {
			$.getScript("//www.google.com/recaptcha/api.js?onload=onloadCaptchaCallback&render=explicit&hl=en");
		}

		/**
		 * Is Mac os
		 */
		if (navigator.platform.match(/(Mac)/i)) {
			$html.addClass("mac-os");
		}

		/**
		 * Is Firefox
		 */
		if (typeof InstallTrigger !== 'undefined') $html.addClass("firefox");

		/**
		 * IE Polyfills
		 * @description  Adds some loosing functionality to IE browsers
		 */
		if (isIE) {
			if (isIE === 12) $html.addClass("ie-edge");
			if (isIE === 11) $html.addClass("ie-11");
			if (isIE < 10) $html.addClass("lt-ie-10");
			if (isIE < 11) $html.addClass("ie-10");
		}

		/**
		 * Bootstrap Tooltips
		 * @description Activate Bootstrap Tooltips
		 */
		if (plugins.bootstrapTooltip.length) {
			var tooltipPlacement = plugins.bootstrapTooltip.attr('data-bs-placement');
			initBootstrapTooltip(tooltipPlacement);

			$window.on('resize orientationchange', function () {
				initBootstrapTooltip(tooltipPlacement);
			})
		}

		/**
		 * bootstrapModalDialog
		 * @description Stap vioeo in bootstrapModalDialog
		 */
		if (plugins.bootstrapModalDialog.length > 0) {
			for (var i = 0; i < plugins.bootstrapModalDialog.length; i++) {
				var modalItem = $(plugins.bootstrapModalDialog[i]);

				modalItem.on('hidden.bs.modal', $.proxy(function () {
					var activeModal = $(this),
							rdVideoInside = activeModal.find('video'),
							youTubeVideoInside = activeModal.find('iframe');

					if (rdVideoInside.length) {
						rdVideoInside[0].pause();
					}

					if (youTubeVideoInside.length) {
						var videoUrl = youTubeVideoInside.attr('src');

						youTubeVideoInside
						.attr('src', '')
						.attr('src', videoUrl);
					}
				}, modalItem))
			}
		}

		/**
		 * Popovers
		 * @description Enables Popovers plugin
		 */
		if (plugins.popover.length) {
			if (window.innerWidth < 767) {
				plugins.popover.attr('data-bs-placement', 'bottom');
				plugins.popover.popover();
			} else {
				plugins.popover.popover();
			}
		}

		/**
		 * Bootstrap Buttons
		 * @description  Enable Bootstrap Buttons plugin
		 */
		if (plugins.statefulButton.length) {
			$(plugins.statefulButton).on('click', function () {
				var statefulButtonLoading = $(this).button('loading');

				setTimeout(function () {
					statefulButtonLoading.button('reset')
				}, 2000);
			})
		}

		/**
		 * Bootstrap tabs
		 * @description Activate Bootstrap Tabs
		 */

		if (plugins.bootstrapTabs.length) {
			for (let i = 0; i < plugins.bootstrapTabs.length; i++) {
				let $bootstrapTab = $(plugins.bootstrapTabs[i]);

				let tabs = $bootstrapTab.find('.nav li a');
				for (var t = 0; t < tabs.length; t++) {
					let tab = $(tabs[t]);
					let	target = $(tab.attr('href')) ;

					tab.removeClass('active show');
					target.removeClass('active show');

					if (t === 0) {
						tab.addClass('active show');
						target.addClass('active show');
					}
				}

				// Nav plugin
				if ($bootstrapTab.attr('data-nav') === 'true') {

					let $buttonPrev = $($bootstrapTab.find('[data-nav-prev]')).first(),
						$buttonNext = $($bootstrapTab.find('[data-nav-next]')).first();

					if ($buttonPrev && $buttonNext) {
						let isClicked = true,
							currentIndex = 0,
							$nav = $($bootstrapTab.find('.nav')),
							navItemsLength = $nav.children().length;


						for (let z = 0; z < navItemsLength; z++) {
							$($nav.find('li:eq(' + z + ') a')).on('hidden.bs.tab', function () {
								isClicked = true;
							});
							$($nav.find('li:eq(' + z + ') a')).on('shown.bs.tab', function () {
								isClicked = true;
							});
						}

						$buttonPrev.on('click', (function ($nav) {
							return function () {
								let prevIndex = $($nav.find('.active')).parent().index() - 1;

								if ((currentIndex != prevIndex) && prevIndex >= 0 && isClicked) {
									currentIndex = prevIndex;
									isClicked = false;
									$($nav.find('li:eq(' + prevIndex + ') a')).tab('show');
								}
							}
						})($nav));

						$buttonNext.on('click', (function ($nav) {
							return function () {
								let nextIndex = $($nav.find('.active')).parent().index() + 1;

								if ((currentIndex != nextIndex) && (nextIndex < navItemsLength) && isClicked) {
									currentIndex = nextIndex;
									isClicked = false;
									$($nav.find('li:eq(' + nextIndex + ') a')).tab('show');
								}
							}
						})($nav));
					}
				}
			}
		}

		/**
		 * Bootstrap Collapse
		 */
		if (plugins.bootstrapCollapse.length) {
			for (var i = 0; i < plugins.bootstrapCollapse.length; i++) {
				var $bootstrapCollapseItem = $(plugins.bootstrapCollapse[i]);

				$bootstrapCollapseItem.on('show.bs.collapse', (function ($bootstrapCollapseItem) {
					return function () {
						$bootstrapCollapseItem.addClass('active');
					};
				})($bootstrapCollapseItem));

				$bootstrapCollapseItem.on('hide.bs.collapse', (function ($bootstrapCollapseItem) {
					return function () {
						$bootstrapCollapseItem.removeClass('active');
					};
				})($bootstrapCollapseItem));
			}
		}

		/**
		 * Copyright Year
		 * @description  Evaluates correct copyright year
		 */
		if (plugins.copyrightYear.length) {
			plugins.copyrightYear.text(initialDate.getFullYear());
		}

		/**
		 * Select2
		 * @description Enables select2 plugin
		 */
		if (plugins.selectFilter.length) {
			for (var i = 0; i < plugins.selectFilter.length; i++) {
				var select = $(plugins.selectFilter[i]);
				let parent = select.closest('div')
				select.select2({
					dropdownParent:          $( parent ),
					placeholder:             select.attr( 'data-placeholder' ) || null,
					minimumResultsForSearch: select.attr( 'data-minimum-results-search' ) || Infinity,
					containerCssClass:       select.attr( 'data-container-class' ) || null,
					dropdownCssClass:        select.attr( 'data-dropdown-class' ) || null
				});
			}
		}

		/**
		 * Radio
		 * @description Add custom styling options for input[type="radio"]
		 */
		if (plugins.radio.length) {
			for (var i = 0; i < plugins.radio.length; i++) {
				$(plugins.radio[i]).addClass("radio-custom").after("<span class='radio-custom-dummy'></span>")
			}
		}

		/**
		 * Checkbox
		 * @description Add custom styling options for input[type="checkbox"]
		 */
		if (plugins.checkbox.length) {
			for (var i = 0; i < plugins.checkbox.length; i++) {
				$(plugins.checkbox[i]).addClass("checkbox-custom").after("<span class='checkbox-custom-dummy'></span>")
			}
		}

		/**
		 * UI To Top
		 * @description Enables ToTop Button
		 */
		if (isDesktop && !isNoviBuilder) {
			$().UItoTop({
				easingType:     'easeOutQuad',
				containerClass: 'ui-to-top fa fa-angle-up'
			});
		}

		/**
		 * Owl carousel
		 * @description Enables Owl carousel plugin
		 */
		if (plugins.owl.length) {
			for (var i = 0; i < plugins.owl.length; i++) {
				var c = $(plugins.owl[i]);
				plugins.owl[i].owl = c;

				initOwlCarousel(c);
			}
		}

		// Filter carousel items
		var notCarouselItems = [];
		for (var z = 0; z < plugins.lightGalleryItem.length; z++) {
			if (!$(plugins.lightGalleryItem[z]).parents('.owl-carousel').length &&
					!$(plugins.lightGalleryItem[z]).parents('.swiper-slider').length &&
					!$(plugins.lightGalleryItem[z]).parents('.slick-slider').length) {
				notCarouselItems.push(plugins.lightGalleryItem[z]);
			}
		}

		plugins.lightGalleryItem = notCarouselItems;

		if (plugins.lightGallery.length) {
			for (var i = 0; i < plugins.lightGallery.length; i++) {
				initLightGallery(plugins.lightGallery[i]);
			}
		}

		if (plugins.lightGalleryItem.length) {
			for (var i = 0; i < plugins.lightGalleryItem.length; i++) {
				initLightGalleryItem(plugins.lightGalleryItem[i]);
			}
		}

		if (plugins.lightDynamicGalleryItem.length) {
			for (var i = 0; i < plugins.lightDynamicGalleryItem.length; i++) {
				initDynamicLightGallery(plugins.lightDynamicGalleryItem[i]);
			}
		}

		/**
		 * RD Navbar
		 * @description Enables RD Navbar plugin
		 */
		if (plugins.rdNavbar.length) {
			var aliaces, i, j, len, value, values, responsiveNavbar;

			aliaces = ["-", "-sm-", "-md-", "-lg-", "-xl-", "-xxl-"];
			values = [0, 576, 768, 992, 1200, 1600];
			responsiveNavbar = {};

			for (i = j = 0, len = values.length; j < len; i = ++j) {
				value = values[i];
				if (!responsiveNavbar[values[i]]) {
					responsiveNavbar[values[i]] = {};
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'layout')) {
					responsiveNavbar[values[i]].layout = plugins.rdNavbar.attr('data' + aliaces[i] + 'layout');
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'device-layout')) {
					responsiveNavbar[values[i]]['deviceLayout'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'device-layout');
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'hover-on')) {
					responsiveNavbar[values[i]]['focusOnHover'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'hover-on') === 'true';
				}
				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'auto-height')) {
					responsiveNavbar[values[i]]['autoHeight'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'auto-height') === 'true';
				}

				if (isNoviBuilder) {
					responsiveNavbar[values[i]]['stickUp'] = false;
				} else if (plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up')) {
					responsiveNavbar[values[i]]['stickUp'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up') === 'true';
				}

				if (plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up-offset')) {
					responsiveNavbar[values[i]]['stickUpOffset'] = plugins.rdNavbar.attr('data' + aliaces[i] + 'stick-up-offset');
				}
			}

			plugins.rdNavbar.RDNavbar({
				anchorNav:    !isNoviBuilder,
				stickUpClone: (plugins.rdNavbar.attr("data-stick-up-clone") && !isNoviBuilder) ? plugins.rdNavbar.attr("data-stick-up-clone") === 'true' : false,
				responsive:   responsiveNavbar,
				callbacks:    {
					onStuck:        function () {
						var navbarSearch = this.$element.find('.rd-search input');

						if (navbarSearch) {
							navbarSearch.val('').trigger('propertychange');
						}

						var navbarSelect = plugins.rdNavbar.find('select');
						if (navbarSelect.length) {
							navbarSelect.select2("close");
						}
					},
					onDropdownOver: function () {
						return !isNoviBuilder;
					},
					onUnstuck:      function () {
						if (this.$clone === null)
							return;

						var navbarSearch = this.$clone.find('.rd-search input');

						if (navbarSearch) {
							navbarSearch.val('').trigger('propertychange');
							navbarSearch.trigger('blur');
						}

					}
				}
			});

			if (plugins.rdNavbar.attr("data-body-class")) {
				document.body.className += ' ' + plugins.rdNavbar.attr("data-body-class");
			}
		}

		/**
		 * RD Search
		 * @description Enables search
		 */
		if (plugins.search.length || plugins.searchResults) {
			var handler = "bat/rd-search.php";
			var defaultTemplate = '<h6 class="search-title"><a target="_top" href="#{href}" class="search-link">#{title}</a></h6>' +
					'<p>...#{token}...</p>' +
					'<p class="match"><em>Terms matched: #{count} - URL: #{href}</em></p>';
			var defaultFilter = '*.html';

			if (plugins.search.length) {
				for (var i = 0; i < plugins.search.length; i++) {
					var searchItem = $(plugins.search[i]),
							options = {
								element:   searchItem,
								filter:    (searchItem.attr('data-search-filter')) ? searchItem.attr('data-search-filter') : defaultFilter,
								template:  (searchItem.attr('data-search-template')) ? searchItem.attr('data-search-template') : defaultTemplate,
								live:      (searchItem.attr('data-search-live')) ? searchItem.attr('data-search-live') : false,
								liveCount: (searchItem.attr('data-search-live-count')) ? parseInt(searchItem.attr('data-search-live'), 10) : 4,
								current:   0,
								processed: 0,
								timer:     {}
							};

					var $toggle = $('.rd-navbar-search-toggle');
					if ($toggle.length) {
						$toggle.on('click', (function (searchItem) {
							return function () {
								if (!($(this).hasClass('active'))) {
									searchItem.find('input').val('').trigger('propertychange');
								}
							}
						})(searchItem));
					}

					if (options.live) {
						var clearHandler = false;

						searchItem.find('input').on("input propertychange", $.proxy(function () {
							this.term = this.element.find('input').val().trim();
							this.spin = this.element.find('.input-group-addon');

							clearTimeout(this.timer);

							if (this.term.length > 2) {
								this.timer = setTimeout(liveSearch(this), 200);

								if (clearHandler === false) {
									clearHandler = true;

									$body.on("click", function (e) {
										if ($(e.toElement).parents('.rd-search').length === 0) {
											$('#rd-search-results-live').addClass('cleared').html('');
										}
									})
								}

							} else if (this.term.length === 0) {
								$('#' + this.live).addClass('cleared').html('');
							}
						}, options, this));
					}

					searchItem.submit($.proxy(function () {
						$('<input />').attr('type', 'hidden')
						.attr('name', "filter")
						.attr('value', this.filter)
						.appendTo(this.element);
						return true;
					}, options, this))
				}
			}

			if (plugins.searchResults.length) {
				var regExp = /\?.*s=([^&]+)\&filter=([^&]+)/g;
				var match = regExp.exec(location.search);

				if (match !== null) {
					$.get(handler, {
						s:        decodeURI(match[1]),
						dataType: "html",
						filter:   match[2],
						template: defaultTemplate,
						live:     ''
					}, function (data) {
						plugins.searchResults.html(data);
					})
				}
			}
		}

		/**
		 * ViewPort Universal
		 * @description Add class in viewport
		 */
		if (plugins.viewAnimate.length) {
			for (var i = 0; i < plugins.viewAnimate.length; i++) {
				var $view = $(plugins.viewAnimate[i]).not('.active');
				$document.on("scroll", $.proxy(function () {
					if (isScrolledIntoView(this)) {
						this.addClass("active");
					}
				}, $view))
				.trigger("scroll");
			}
		}

		/**
		 * Swiper
		 * @description  Enable Swiper Slider
		 */
		if (plugins.swiper.length) {

			for (let i = 0; i < plugins.swiper.length; i++) {

				let
					node = plugins.swiper[i],
					pag = $(node).find(".swiper-pagination"),
					params = parseJSON(node.getAttribute('data-swiper')),
					defaults = {
						speed:      1000,
						loop:       true,
						pagination: {
							el:        '.swiper-pagination',
							clickable: true,
						},
						navigation: {
							nextEl: '.swiper-button-next',
							prevEl: '.swiper-button-prev'
						},
						autoplay:   {
							delay: 5000
						}
					},
					xMode = {
						autoplay:      false,
						loop:          false,
						simulateTouch: false
					};

				defaults.pagination.renderBullet =  pag.length ? pag.attr("data-index-bullet") === "true" ? renderBullet : null : null;

				params.on = {
						init: function () {
							setBackgrounds(this);
							setHoverAutoplay(this);
							
							toggleSwiperCaptionAnimation(this);
							initLightGalleryItem($(node).find('[data-lightgallery="item"]'), 'lightGallery-in-carousel');

							// Real Previous Index must be set recent
							this.on('slideChangeTransitionEnd', function () {
								toggleSwiperCaptionAnimation(this);
							});
						}
				};

				new Swiper( node, Util.merge( isNoviBuilder ? [ defaults, params, xMode ] : [ defaults, params ] ) );
			}
		}

		/**
		 * WOW
		 * @description Enables Wow animation plugin
		 */
		if ($html.hasClass("wow-animation") && plugins.wow.length && !isNoviBuilder && isDesktop) {
			new WOW().init();
		}

		// RD Input Label
		if (plugins.rdInputLabel.length) {
			plugins.rdInputLabel.RDInputLabel();
		}

		// Regula
		if (plugins.regula.length) {
			attachFormValidator(plugins.regula);
		}

		// MailChimp Ajax subscription
		if (plugins.mailchimp.length) {
			for (i = 0; i < plugins.mailchimp.length; i++) {
				var $mailchimpItem = $(plugins.mailchimp[i]),
						$email = $mailchimpItem.find('input[type="email"]');

				// Required by MailChimp
				$mailchimpItem.attr('novalidate', 'true');
				$email.attr('name', 'EMAIL');

				$mailchimpItem.on('submit', $.proxy(function ($email, event) {
					event.preventDefault();

					var $this = this;

					var data = {},
							url = $this.attr('action').replace('/post?', '/post-json?').concat('&c=?'),
							dataArray = $this.serializeArray(),
							$output = $("#" + $this.attr("data-form-output"));

					for (i = 0; i < dataArray.length; i++) {
						data[dataArray[i].name] = dataArray[i].value;
					}

					$.ajax({
						data:       data,
						url:        url,
						dataType:   'jsonp',
						error:      function (resp, text) {
							$output.html('Server error: ' + text);

							setTimeout(function () {
								$output.removeClass("active");
							}, 4000);
						},
						success:    function (resp) {
							$output.html(resp.msg).addClass('active');
							$email[0].value = '';
							var $label = $('[for="' + $email.attr('id') + '"]');
							if ($label.length) $label.removeClass('focus not-empty');

							setTimeout(function () {
								$output.removeClass("active");
							}, 6000);
						},
						beforeSend: function (data) {
							var isNoviBuilder = window.xMode;

							var isValidated = (function () {
								var results, errors = 0;
								var elements = $this.find('[data-constraints]');
								var captcha = null;
								if (elements.length) {
									for (var j = 0; j < elements.length; j++) {

										var $input = $(elements[j]);
										if ((results = $input.regula('validate')).length) {
											for (var k = 0; k < results.length; k++) {
												errors++;
												$input.siblings(".form-validation").text(results[k].message).parent().addClass("has-error");
											}
										} else {
											$input.siblings(".form-validation").text("").parent().removeClass("has-error")
										}
									}

									if (captcha) {
										if (captcha.length) {
											return validateReCaptcha(captcha) && errors === 0
										}
									}

									return errors === 0;
								}
								return true;
							})();

							// Stop request if builder or inputs are invalide
							if (isNoviBuilder || !isValidated)
								return false;

							$output.html('Submitting...').addClass('active');
						}
					});

					return false;
				}, $mailchimpItem, $email));
			}
		}

		// Campaign Monitor ajax subscription
		if (plugins.campaignMonitor.length) {
			for (i = 0; i < plugins.campaignMonitor.length; i++) {
				var $campaignItem = $(plugins.campaignMonitor[i]);

				$campaignItem.on('submit', $.proxy(function (e) {
					var data = {},
							url = this.attr('action'),
							dataArray = this.serializeArray(),
							$output = $("#" + plugins.campaignMonitor.attr("data-form-output")),
							$this = $(this);

					for (i = 0; i < dataArray.length; i++) {
						data[dataArray[i].name] = dataArray[i].value;
					}

					$.ajax({
						data:       data,
						url:        url,
						dataType:   'jsonp',
						error:      function (resp, text) {
							$output.html('Server error: ' + text);

							setTimeout(function () {
								$output.removeClass("active");
							}, 4000);
						},
						success:    function (resp) {
							$output.html(resp.Message).addClass('active');

							setTimeout(function () {
								$output.removeClass("active");
							}, 6000);
						},
						beforeSend: function (data) {
							// Stop request if builder or inputs are invalide
							if (isNoviBuilder || !isValidated($this.find('[data-constraints]')))
								return false;

							$output.html('Submitting...').addClass('active');
						}
					});

					// Clear inputs after submit
					var inputs = $this[0].getElementsByTagName('input');
					for (var i = 0; i < inputs.length; i++) {
						inputs[i].value = '';
						var label = document.querySelector('[for="' + inputs[i].getAttribute('id') + '"]');
						if (label) label.classList.remove('focus', 'not-empty');
					}

					return false;
				}, $campaignItem));
			}
		}

		// RD Mailform
		if (plugins.rdMailForm.length) {
			var i, j, k,
					msg = {
						'MF000': 'Successfully sent!',
						'MF001': 'Recipients are not set!',
						'MF002': 'Form will not work locally!',
						'MF003': 'Please, define email field in your form!',
						'MF004': 'Please, define type of your form!',
						'MF254': 'Something went wrong with PHPMailer!',
						'MF255': 'Aw, snap! Something went wrong.'
					};

			for (i = 0; i < plugins.rdMailForm.length; i++) {
				var $form = $(plugins.rdMailForm[i]),
						formHasCaptcha = false;

				$form.attr('novalidate', 'novalidate').ajaxForm({
					data:         {
						"form-type": $form.attr("data-form-type") || "contact",
						"counter":   i
					},
					beforeSubmit: function (arr, $form, options) {
						if (isNoviBuilder)
							return;

						var form = $(plugins.rdMailForm[this.extraData.counter]),
								inputs = form.find("[data-constraints]"),
								output = $("#" + form.attr("data-form-output")),
								captcha = form.find('.recaptcha'),
								captchaFlag = true;

						output.removeClass("active error success");

						if (isValidated(inputs, captcha)) {

							// veify reCaptcha
							if (captcha.length) {
								var captchaToken = captcha.find('.g-recaptcha-response').val(),
										captchaMsg = {
											'CPT001': 'Please, setup you "site key" and "secret key" of reCaptcha',
											'CPT002': 'Something wrong with google reCaptcha'
										};

								formHasCaptcha = true;

								$.ajax({
									method: "POST",
									url:    "bat/reCaptcha.php",
									data:   {'g-recaptcha-response': captchaToken},
									async:  false
								})
								.done(function (responceCode) {
									if (responceCode !== 'CPT000') {
										if (output.hasClass("snackbars")) {
											output.html('<p><span class="icon text-middle mdi mdi-check icon-xxs"></span><span>' + captchaMsg[responceCode] + '</span></p>')

											setTimeout(function () {
												output.removeClass("active");
											}, 3500);

											captchaFlag = false;
										} else {
											output.html(captchaMsg[responceCode]);
										}

										output.addClass("active");
									}
								});
							}

							if (!captchaFlag) {
								return false;
							}

							form.addClass('form-in-process');

							if (output.hasClass("snackbars")) {
								output.html('<p><span class="icon text-middle fa fa-circle-o-notch fa-spin icon-xxs"></span><span>Sending</span></p>');
								output.addClass("active");
							}
						} else {
							return false;
						}
					},
					error:        function (result) {
						if (isNoviBuilder)
							return;

						var output = $("#" + $(plugins.rdMailForm[this.extraData.counter]).attr("data-form-output")),
								form = $(plugins.rdMailForm[this.extraData.counter]);

						output.text(msg[result]);
						form.removeClass('form-in-process');

						if (formHasCaptcha) {
							grecaptcha.reset();
						}
					},
					success:      function (result) {
						if (isNoviBuilder)
							return;

						var form = $(plugins.rdMailForm[this.extraData.counter]),
								output = $("#" + form.attr("data-form-output")),
								select = form.find('select');

						form
						.addClass('success')
						.removeClass('form-in-process');

						if (formHasCaptcha) {
							grecaptcha.reset();
						}

						result = result.length === 5 ? result : 'MF255';
						output.text(msg[result]);

						if (result === "MF000") {
							if (output.hasClass("snackbars")) {
								output.html('<p><span class="icon text-middle mdi mdi-check icon-xxs"></span><span>' + msg[result] + '</span></p>');
							} else {
								output.addClass("active success");
							}
						} else {
							if (output.hasClass("snackbars")) {
								output.html(' <p class="snackbars-left"><span class="icon icon-xxs mdi mdi-alert-outline text-middle"></span><span>' + msg[result] + '</span></p>');
							} else {
								output.addClass("active error");
							}
						}

						form.clearForm();

						if (select.length) {
							select.val(null).trigger('change');
						}

						form.find('input, textarea').trigger('blur');

						setTimeout(function () {
							output.removeClass("active error success");
							form.removeClass('success');
						}, 3500);
					}
				});
			}
		}

		/**
		 * Stepper
		 * @description Enables Stepper Plugin
		 */
		if (plugins.stepper.length) {
			plugins.stepper.stepper({
				labels: {
					up:   "",
					down: ""
				}
			});
		}

		/**
		 * Custom Toggles
		 */
		if (plugins.customToggle.length) {
			for (var i = 0; i < plugins.customToggle.length; i++) {
				var $this = $(plugins.customToggle[i]);

				$this.on('click', $.proxy(function (event) {
					event.preventDefault();

					var $ctx = $(this);
					$($ctx.attr('data-custom-toggle')).add(this).toggleClass('active');
				}, $this));

				if ($this.attr("data-custom-toggle-hide-on-blur") === "true") {
					$body.on("click", $this, function (e) {
						if (e.target !== e.data[0]
								&& $(e.data.attr('data-custom-toggle')).find($(e.target)).length
								&& e.data.find($(e.target)).length === 0) {
							$(e.data.attr('data-custom-toggle')).add(e.data[0]).removeClass('active');
						}
					})
				}

				if ($this.attr("data-custom-toggle-disable-on-blur") === "true") {
					$body.on("click", $this, function (e) {
						if (e.target !== e.data[0]
								&& $(e.data.attr('data-custom-toggle')).find($(e.target)).length === 0
								&& e.data.find($(e.target)).length === 0) {
							$(e.data.attr('data-custom-toggle')).add(e.data[0]).removeClass('active');
						}
					})
				}
			}
		}

		/**
		 * Material Parallax
		 * @description Enables Material Parallax plugin
		 */
		if (plugins.materialParallax.length) {
			if (!isNoviBuilder && !isIE && !isMobile) {
				plugins.materialParallax.parallax();

				// heavy pages fix
				$window.on('load', function () {
					setTimeout(function () {
						$window.scroll();
					}, 500);
				});
			} else {
				for (var i = 0; i < plugins.materialParallax.length; i++) {
					var parallax = $(plugins.materialParallax[i]),
							imgPath = parallax.data("parallax-img");

					parallax.css({
						"background-image": 'url(' + imgPath + ')',
						"background-size":  "cover"
					});
				}
			}
		}

		/**
		 * RD Range
		 * @description Enables RD Range plugin
		 */
		if (plugins.rdRange.length && !isNoviBuilder) {
			plugins.rdRange.RDRange({});
		}

		if (plugins.inlineToggle.length) {
			for (var i = 0; i < plugins.inlineToggle.length; i++) {
				var $element = $(plugins.inlineToggle[i]);

				$element.on('click', (function ($element) {
					return function (event) {
						event.preventDefault();
						$element.parents(".inline-toggle-parent").toggleClass("active");
					}
				})($element));

				$body.on('click', $element, (function ($element) {
					return function (event) {
						if (event.target !== event.data[0]
								&& !$(event.target).hasClass("inline-toggle-parent")
								&& event.data.find($(event.target)).length === 0) {

							$element.parents(".inline-toggle-parent").removeClass("active");
						}
					}
				})($element));
			}
		}

		/**
		 * Focus toggle
		 */
		if (plugins.focusToggle.length) {
			for (var i = 0; i < plugins.focusToggle.length; i++) {
				var $element = $(plugins.focusToggle[i]);

				$element.hover(
						function (event) {
							event.preventDefault();
							$(this).parents('.focus-toggle-parent').addClass('focus');
						}
				);

				$element.parents('.focus-toggle-parent').hover(
						function () {
						},
						function () {
							$(this).removeClass('focus');
						}
				)
			}
		}

		// Radio Panel
		if (plugins.radioPanel) {
			for (var i = 0; i < plugins.radioPanel.length; i++) {
				var $element = $(plugins.radioPanel[i]);
				$element.on('click', function () {
					plugins.radioPanel.removeClass('active');
					$(this).addClass('active');
				})
			}
		}

		// Slick carousel
		if (plugins.slick.length) {
			for (var i = 0; i < plugins.slick.length; i++) {
				var $slickItem = $(plugins.slick[i]);

				$slickItem.on('init', function (slick) {
					initLightGallery($(slick).find('[data-lightgallery="item"]'), 'lightGallery-in-carousel');
				});

				$slickItem.slick({
					slidesToScroll: parseInt($slickItem.attr('data-slide-to-scroll'), 10) || 1,
					asNavFor:       $slickItem.attr('data-for') || false,
					dots:           $slickItem.attr("data-dots") === "true",
					infinite:       isNoviBuilder ? false : $slickItem.attr("data-loop") === "true",
					focusOnSelect:  true,
					arrows:         $slickItem.attr("data-arrows") === "true",
					swipe:          $slickItem.attr("data-swipe") === "true",
					autoplay:       isNoviBuilder ? false : $slickItem.attr("data-autoplay") === "true",
					autoplaySpeed:  $slickItem.attr("data-autoplay-speed") ? parseInt($slickItem.attr("data-autoplay-speed")) : 3500,
					vertical:       $slickItem.attr("data-vertical") === "true",
					centerMode:     $slickItem.attr("data-center-mode") === "true",
					centerPadding:  $slickItem.attr("data-center-padding") ? $slickItem.attr("data-center-padding") : '0.50',
					mobileFirst:    true,
					rtl:            isRtl,
					responsive:     [
						{
							breakpoint: 0,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-items'), 10) || 1
							}
						},
						{
							breakpoint: 575,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-sm-items'), 10) || 1
							}
						},
						{
							breakpoint: 767,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-md-items'), 10) || 1
							}
						},
						{
							breakpoint: 991,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-lg-items'), 10) || 1
							}
						},
						{
							breakpoint: 1199,
							settings:   {
								slidesToShow: parseInt($slickItem.attr('data-xl-items'), 10) || 1
							}
						}
					]
				})
				.on('afterChange', function (event, slick, currentSlide, nextSlide) {
					var $this = $(this),
							childCarousel = $this.attr('data-child');

					if (childCarousel) {
						$(childCarousel + ' .slick-slide').removeClass('slick-current');
						$(childCarousel + ' .slick-slide').eq(currentSlide).addClass('slick-current');
					}
				});
			}
		}

		// Video Overlay
		if (plugins.videoOverlay.length) {
			for (var i = 0; i < plugins.videoOverlay.length; i++) {
				var overlay = plugins.videoOverlay[i];
				if (overlay) {
					overlay.style.opacity = '1';
					overlay.addEventListener('click', function (e) {
						$(this).animate({
							opacity: 0
						}, function () {
							this.style.display = 'none';
						});
					});
				}
			}
		}

		// Lazy iframe
		if (plugins.lazyIFrame.length) {
			for (let i = 0; i < plugins.lazyIFrame.length; i++) {
				lazyInit( $(plugins.lazyIFrame[i]), function () {
					this.attr('src', this.attr('data-src'));
				});
			}
		}
		// ThemeSwitcher
		if (plugins.themeSwitcher.length) {
			document.documentElement.addEventListener('theme-switching', function () {
				if (windowReady) {
					loaderTimeoutId = setTimeout(function () {
						plugins.preloader.removeClass("loaded");
					}, 500);
				}
			});

			document.documentElement.addEventListener('theme-switched', function (event) {
				if (windowReady) {
					clearTimeout(loaderTimeoutId);
					setTimeout(function () {
						if (windowReady) {
							plugins.preloader.addClass("loaded");
						}
					}, 400);
				}
			});

			document.documentElement.addEventListener('theme-color-change', function (event) {
				document.querySelector(event.switcher.selectors.color + '[name="' + event.variable + '"]').style.backgroundColor = event.value;
			});

			var switcher = themeSwitcherInit({
				variablesFallback: isIE,
				cookie:            false,
				themes:            {
					"soccer":     {
						styles: 'css/style.css'
					},
					"baseball":   {
						styles: 'css/style-baseball.css'
					},
					"basketball": {
						styles: 'css/style-basketball.css'
					},
					"billiards":  {
						styles: 'css/style-billiards.css'
					},
					"bowling":    {
						styles: 'css/style-bowling.css'
					},
					"rugby":      {
						styles: 'css/style-rugby.css'
					},
					"tennis":      {
						styles: 'css/style-tennis.css'
					}
				}
			});
		}
	});
}());
