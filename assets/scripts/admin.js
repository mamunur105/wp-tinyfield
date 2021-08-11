"use strict";

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function ($) {
  'use strict';
  /**
   * All of the code for your Dashboard-specific JavaScript source
   * should reside in this file.
   *
   * Note that this assume you're going to use jQuery, so it prepares
   * the $ function reference to be used within the scope of this
   * function.
   *
   * From here, you're able to define handlers for when the DOM is
   * ready:
   *
   * $(function() {
   *
   * });
   *
   * Or when the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and so on.
   *
   * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
   * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
   * be doing this, we should try to minimize doing that in our own work.
   */

  var CODEXIN_METABOX = {};
  var $window = $(window),
      $document = $(document),
      $select2 = $('.selectbox-wraper select'),
      $image_upload = $('.fields-wrapper.image-upload'),
      $galleryimage = $('.image-gallery'),
      $colorpicker = $('.field-colorpicker'),
      $conditional = $('.conditional-field'); // Check if element exists

  var cxExists = function cxExists(el) {
    return el.length > 0;
  };
  /************************************************************
         01 - colorpicker
     *************************************************************/


  CODEXIN_METABOX.wpColorPicker = function () {
    if (cxExists($colorpicker)) {
      $colorpicker.wpColorPicker();
    }
  };
  /************************************************************
         02 - Select2 activation
     *************************************************************/


  CODEXIN_METABOX.select2 = function () {
    if (cxExists($select2)) {
      $select2.each(function () {
        var parent = $(this).parent('.selectbox-wraper');
        var multiselect = parent.data('multiselect');
        var selectobj = {
          multiple: false
        };

        if (Boolean(multiselect)) {
          selectobj.multiple = true;
        }

        var selectEl = $(this).select2(_objectSpread({}, selectobj));

        if (Boolean(multiselect)) {
          selectEl.next().children().children().children().sortable({
            containment: 'parent',
            stop: function stop(event, ui) {
              ui.item.parent().children('[title]').each(function () {
                var title = $(this).attr('title');
                var original = $('option:contains(' + title + ')', selectEl).first();
                original.detach();
                selectEl.append(original);
              });
              selectEl.change();
            }
          });
        }
      });
    }
  };
  /************************************************************
         03 - imageUpload activation
     *************************************************************/


  CODEXIN_METABOX.imageUpload = function () {
    if (cxExists($image_upload)) {
      // on upload button click
      $image_upload.on('click', '.upload-btn', function (e) {
        e.preventDefault();
        var button_parent = e.target.closest('.field-wrapper'),
            preview = button_parent.querySelector('.preview-image'),
            input_field = button_parent.querySelector('.image_input_field'),
            custom_uploader = wp.media({
          title: 'Insert image',
          library: {
            type: 'image'
          },
          button: {
            text: 'Use this image' // button label text

          },
          multiple: false
        });
        custom_uploader.on('open', function () {
          // var lib = custom_uploader.state().get('library');
          var ids_value = input_field.value.split(',');
          var selection = custom_uploader.state().get('selection'); // lib.comparator = function( a, b ) {
          // 	var aInQuery = !! this.mirroring.get( a.cid ),
          // 		bInQuery = !! this.mirroring.get( b.cid );
          // 	if ( ! aInQuery && bInQuery ) {
          // 		return -1;
          // 	} else if ( aInQuery && ! bInQuery ) {
          // 		return 1;
          // 	} else {
          // 		return 0;
          // 	}
          // };

          ids_value.forEach(function (id) {
            var attachment = wp.media.attachment(id);
            attachment.fetch(); // lib.add( attachment ? [ attachment ] : [] );

            selection.add(attachment ? [attachment] : []);
          });
        });
        custom_uploader.on('select', function () {
          // it also has "open" and "close" events
          var attachment = custom_uploader.state().get('selection').first().toJSON();
          preview.style.backgroundImage = 'url(' + attachment.url + ')';
          input_field.value = attachment.id;
          button_parent.querySelector('.preview-wrap').classList.remove('button-hide');
        });
        custom_uploader.open();
      });
      $image_upload.on('click', '.metabox-image-remove', function (e) {
        e.preventDefault();
        var button_parent = e.target.closest('.field-wrapper'),
            preview = button_parent.querySelector('.preview-image'),
            input_field = button_parent.querySelector('.image_input_field');
        input_field.value = '';
        preview.style.backgroundImage = 'url(https://via.placeholder.com/700x200)';
        button_parent.querySelector('.preview-wrap').classList.add('button-hide');
      });
    }
  };
  /************************************************************
         03 - imageUpload activation
     *************************************************************/


  CODEXIN_METABOX.galleryImage = function () {
    if (cxExists($galleryimage)) {
      // on upload button click
      $galleryimage.on('click', '.upload-btn', function (e) {
        e.preventDefault();
        var button_parent = e.target.closest('.field-wrapper'),
            preview = button_parent.querySelector('.preview-list'),
            input_field = button_parent.querySelector('.image_input_field'),
            custom_uploader = wp.media({
          title: 'Insert image',
          library: {
            type: 'image'
          },
          button: {
            text: 'Use this image' // button label text

          },
          multiple: 'add'
        });
        custom_uploader.on('open', function () {
          // var lib = custom_uploader.state().get('library');
          var ids_value = input_field.value.split(',');
          var selection = custom_uploader.state().get('selection'); // lib.comparator = function( a, b ) {
          // 	var aInQuery = !! this.mirroring.get( a.cid ),
          // 		bInQuery = !! this.mirroring.get( b.cid );
          // 	if ( ! aInQuery && bInQuery ) {
          // 		return -1;
          // 	} else if ( aInQuery && ! bInQuery ) {
          // 		return 1;
          // 	} else {
          // 		return 0;
          // 	}
          // };

          ids_value.forEach(function (id) {
            var attachment = wp.media.attachment(id);
            attachment.fetch(); // lib.add( attachment ? [ attachment ] : [] );

            selection.add(attachment ? [attachment] : []);
          });
        });
        custom_uploader.on('select', function () {
          // it also has "open" and "close" events
          var attachment = custom_uploader.state().get('selection').toJSON();
          var image = '';
          var ids = [];
          attachment.forEach(function (item) {
            if (item.id) {
              image += '<li class="preview-wrap" data-id=' + item.id + '><div class="preview-image" style="background-image:url(' + item.url + ')"></div> <button class="metabox-image-remove"><span class="dashicons dashicons-no-alt"></span></button><a href="#" class="metabox-image-edit" target="_blank"> <span class="dashicons dashicons-edit-large"></span> </a> </li>';
              ids.push(item.id);
            }
          });
          preview.innerHTML = image;
          var filtered = ids.filter(function (el) {
            return el != false;
          });
          input_field.value = filtered;
        });
        custom_uploader.open();
      });
      $galleryimage.on('click', '.metabox-image-remove', function (e) {
        e.preventDefault();
        var preview = e.target.closest('.preview-wrap'),
            input_field = e.target.closest('.field-wrapper').querySelector('.image_input_field'),
            preview_id = preview.getAttribute('data-id'),
            input_value = input_field.value.split(','),
            input_value = input_value.filter(function (item) {
          return item !== preview_id;
        });
        preview.remove();
        input_field.value = String(input_value);
      });
      $galleryimage.find('ul.preview-list').sortable({
        opacity: 0.8,
        stop: function stop(event) {
          // console.log( event.target.children );
          var input_field = event.target.closest('.field-wrapper').querySelector('.image_input_field'),
              ids = [];
          Array.from(event.target.children).forEach(function (item) {
            ids.push(item.getAttribute('data-id'));
          });
          var filtered = ids.filter(function (el) {
            return el != false;
          });
          console.log(filtered);
          input_field.value = String(filtered);
        }
      });
    }
  };

  CODEXIN_METABOX.checkBox = function () {
    var selector = $('.fields-wrapper').find("[type=checkbox]");

    if (cxExists(selector)) {
      selector.each(function (index, item) {
        // console.log($(item).attr('name'));
        var curent_value = $(item).val();
        $(item).on('change', function () {
          var curent_value = $(selector).val();

          if (curent_value && 1 == curent_value) {
            $(item).val(0);
          } else if (curent_value && 0 == curent_value) {
            $(item).val(1);
          }
        });
      });
    } // let value = selector.val();
    // console.log( value );

  };

  CODEXIN_METABOX.conditionalField = function () {
    if (cxExists($conditional)) {
      $conditional.each(function (index, item) {
        var $this = $(item);
        var required = $this.attr('data-required-value');
        var selector = '#' + $this.attr('data-required-field');
        selector = selector.replace('field-', "");
        $(selector).on('change', function () {
          console.log('====');
          var curent_value = $(selector).val();

          if (required == curent_value) {
            console.log('required');
          } else {
            console.log('not required');
          }
        });
      });
    }
  }; // Window load functions
  // $window.on('load', function() { });
  // Document ready functions


  $document.on('ready', function () {
    CODEXIN_METABOX.wpColorPicker(), CODEXIN_METABOX.select2(), CODEXIN_METABOX.imageUpload(), CODEXIN_METABOX.galleryImage(), CODEXIN_METABOX.checkBox(), CODEXIN_METABOX.conditionalField();
  });
})(jQuery);