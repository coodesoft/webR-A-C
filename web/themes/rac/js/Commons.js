/**
 * Created by pablo on 09/07/16.
 */

var Commons = {

    formatPrice: function(number, decimals, decPoint, thousandsSep) {

        decimals = decimals ? decimals : 0;
        decPoint = ',';
        thousandsSep = '.';

        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number;
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint;


        var toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                    .toFixed(prec);
        };

        var s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }

        return s.join(dec);
    },

    notify: function(element, message, className, position) {
        var self = this;

        element = element ? element : $;

        element.notify(
            message,
            {
                className: className,
                autoHideDelay: 2000,
                clickToHide: true,
                position: !position ? 'right bottom' : position
            }
        );
    },

    showLoader: function() {
        $(".loader").fadeIn(0);
    },

    hideLoader: function() {
        $(".loader").fadeOut("slow");
    }
};