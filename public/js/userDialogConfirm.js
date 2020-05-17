class userDialogConfirm 
{
  deleteRequest()
  {
    $("#dialog-confirm").hide();
    $(".delete-account").click(function(){
      let that = this
      $("#dialog-confirm").dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
          "Oui": function() {
            $(location).attr('href', $(that).data('url'));
            $(this).dialog("close");
          },
          Non: function() {
            $(this).dialog("close");
          }
        }
      });
    });  
  }
  
  responsiveDialog()
  {
    $.ui.dialog.prototype.options.clickOut = true;
    $.ui.dialog.prototype.options.responsive = true;
    $.ui.dialog.prototype.options.scaleH = 0.8;
    $.ui.dialog.prototype.options.scaleW = 0.8;
    $.ui.dialog.prototype.options.showTitleBar = true;
    $.ui.dialog.prototype.options.showCloseButton = true;

    // extend _init
    var _init = $.ui.dialog.prototype._init;
    $.ui.dialog.prototype._init = function () {
      var self = this;

      // apply original arguments
      _init.apply(this, arguments);

      //patch
      if ($.ui && $.ui.dialog && $.ui.dialog.overlay) {
        $.ui.dialog.overlay.events = $.map('focus,keydown,keypress'.split(','), function (event) {
          return event + '.dialog-overlay';
        }).join(' ');
      }
    };
    // end _init

    // extend open function
    var _open = $.ui.dialog.prototype.open;
    $.ui.dialog.prototype.open = function () {
      var self = this;

      // apply original arguments
      _open.apply(this, arguments);

      // get dialog original size on open
      var oHeight = self.element.parent().outerHeight(),
          oWidth = self.element.parent().outerWidth(),
          isTouch = $("html").hasClass("touch");

      // responsive width & height
      var resize = function () {

        // check if responsive
        // dependent on modernizr for device detection / html.touch
        if (self.options.responsive === true || (self.options.responsive === "touch" && isTouch)) {
          var elem = self.element,
            wHeight = $(window).height(),
            wWidth = $(window).width(),
            dHeight = elem.parent().outerHeight(),
            dWidth = elem.parent().outerWidth(),
            setHeight = Math.min(wHeight * self.options.scaleH, oHeight),
            setWidth = Math.min(wWidth * self.options.scaleW, oWidth);

          // check & set height
          if ((oHeight + 100) > wHeight || elem.hasClass("resizedH")) {
            elem.dialog("option", "height", setHeight).parent().css("max-height", setHeight);
            elem.addClass("resizedH");
          }

          // check & set width
          if ((oWidth + 100) > wWidth || elem.hasClass("resizedW")) {
            elem.dialog("option", "width", setWidth).parent().css("max-width", setWidth);
            elem.addClass("resizedW");
          }

          // only recenter & add overflow if dialog has been resized
          if (elem.hasClass("resizedH") || elem.hasClass("resizedW")) {
            elem.dialog("option", "position", "center");
            elem.css("overflow", "auto");
          }
        }

        // add webkit scrolling to all dialogs for touch devices
        if (isTouch) {
          elem.css("-webkit-overflow-scrolling", "touch");
        }
      };

      // call resize()
      resize();

      // resize on window resize
      $(window).on("resize", function () {
        resize();
      });

      // resize on orientation change
      if (window.addEventListener) {  // Add extra condition because IE8 doesn't support addEventListener (or orientationchange)
        window.addEventListener("orientationchange", function () {
          resize();
        });
      }

      // hide titlebar
      if (!self.options.showTitleBar) {
        self.uiDialogTitlebar.css({
          "height": 0,
          "padding": 0,
          "background": "none",
          "border": 0
        });
        self.uiDialogTitlebar.find(".ui-dialog-title").css("display", "none");
      }

      //hide close button
      if (!self.options.showCloseButton) {
        self.uiDialogTitlebar.find(".ui-dialog-titlebar-close").css("display", "none");
      }

      // close on clickOut
      if (self.options.clickOut && !self.options.modal) {
        // use transparent div - simplest approach (rework)
        $('<div id="dialog-overlay"></div>').insertBefore(self.element.parent());
        $('#dialog-overlay').css({
          "position": "fixed",
          "top": 0,
          "right": 0,
          "bottom": 0,
          "left": 0,
          "background-color": "transparent"
        });
        $('#dialog-overlay').click(function (e) {
          e.preventDefault();
          e.stopPropagation();
          self.close();
        });
        // else close on modal click
      } 
      else if (self.options.clickOut && self.options.modal) {
        $('.ui-widget-overlay').click(function (e) {
          self.close();
        });
      }

      // add dialogClass to overlay
      if (self.options.dialogClass) {
        $('.ui-widget-overlay').addClass(self.options.dialogClass);
      }
    };
    //end open

    // extend close function
    var _close = $.ui.dialog.prototype.close;
    $.ui.dialog.prototype.close = function () {
      var self = this;
      // apply original arguments
      _close.apply(this, arguments);

      // remove dialogClass to overlay
      if (self.options.dialogClass) {
        $('.ui-widget-overlay').removeClass(self.options.dialogClass);
      }
      //remove clickOut overlay
      if ($("#dialog-overlay").length) {
        $("#dialog-overlay").remove();
      }
    };
    //end close
  }
}
$(function(){
  const confirmRequest = new userDialogConfirm;
  confirmRequest.deleteRequest()
  confirmRequest.responsiveDialog()
})
