var inputNumbers = ''; //used to format value.
jQuery(function ($) {
    jQuery(document).bind('gform_post_render', function () {

        // code to trigger everytime the form is refreshed.

        //if there is a credit card, hide the month and year dropdowns and add this input
        var $tabindex = $('.ginput_card_expiration_month').attr('tabindex');

        //needs id with emmets or gravity forms breaks...
        $('.ginput_card_expiration_container').prepend('<input placeholder="MM / YY" id="special_field" type="text" name="expire_special" class="expire-special" tabindex="' + $tabindex + '">');
        $('.ginput_card_expiration_container select').addClass('gfield_visibility_hidden');

    });

    //this stuff doesn't need to be on every refresh.
    //on click position at end of input
    $('.gform_wrapper').on('click focus', '.expire-special', function () {
        var element = $(this)[0];
        var length = $(this).val().length;

        setCursorPosition(element, length);
    });

    //based loosely on http://jsfiddle.net/nirodhasoftware/pgfcpxeb/

    $('.gform_wrapper').on("keydown", '.expire-special', function (e) {
        var key = e.which; // e.keyCode

        var digit = ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)),
            backspace = (key == 8),
            escape = (key == 27),
            tab = (key == 9),
            fwdslash = (key == 191 || key == 111),
            commandctrl = e.metaKey;


        switch (true) {
            case digit:
                e.preventDefault();

                var addition = GetCharacterFromKeyCode(key);

                //if first number is anything but 0 or 1, then it must have 0 before it.
                if (inputNumbers.length === 0 && addition != '0' && addition != '1') {
                    inputNumbers += '0' + addition + ' / ';
                }

                //second number must be only 1 or 2 or 0 if the first one is not 0
                else if (inputNumbers.length === 1) {
                    //if first number was a 1
                    if (inputNumbers === '1' && $.inArray(addition, ['1', '2', '0']) != -1) {
                        inputNumbers += addition + ' / ';
                    }
                    //if first number was 0 it can be any number > 0
                    else if (inputNumbers === '0' && addition != '0') {
                        inputNumbers += addition + ' / ';
                    }
                }

                else if (inputNumbers.length == 2) {
                    inputNumbers += ' / ' + addition;
                }

                //were up to second numbers after /
                else if ((inputNumbers.length > 2 && inputNumbers.length < 7 ) || inputNumbers.length == 0) {
                    inputNumbers += addition;
                }
                break;

            //if they type one number and then /  add 0
            case fwdslash:
                e.preventDefault();
                if (inputNumbers.length == 1 && inputNumbers === '1') {
                    inputNumbers = "01 / ";
                }
                else if (inputNumbers.length == 2) {
                    inputNumbers += ' / ';
                }
                break;

            case backspace:
                //remove last char from the inputNumbers
                e.preventDefault();
                if (inputNumbers.length > 0) {
                    lastChar = inputNumbers.slice(-1);
                    inputNumbers = inputNumbers.slice(0, -1);

                    if (lastChar === ' ' || lastChar === '/') {
                        inputNumbers = inputNumbers.substr(0, 2);
                    }
                }
                break;

            case escape:
                //clear it
                inputNumbers = '';
                e.preventDefault();
                return;

            case (tab || commandctrl ):
                return;
                break;
            default:
                e.preventDefault();
                break;
        }

        $('.expire-special').val(inputNumbers);
        setGformSelects(inputNumbers);
    });

    //when its blurred make sure the year is corrected
    $('.gform_wrapper').on("blur", '.expire-special', function (e) {

        //has / with 1 number after make 01...
        if (inputNumbers.length == 6) {
            inputNumbers = inputNumbers.substr(0, 5) + 0 + inputNumbers.substr(5, 5);
            $('.expire-special').val(inputNumbers);
            setGformSelects(inputNumbers);
        }

        else if (inputNumbers.length < 6) {
            inputNumbers = '';
            $('.expire-special').val(inputNumbers);
            setGformSelects(inputNumbers);
        }

    });

});

function GetCharacterFromKeyCode(key) {
    return String.fromCharCode(((key >= 96 && key <= 105) ? key - 48 : key));
}


function setCursorPosition(element, pos) {
    element.setSelectionRange(pos, pos);
    return;
}

function setGformSelects(inputNumbers) {

    //must have all 7 characters 11 / 11
    if (inputNumbers.length == 7) {
        var month = inputNumbers.substr(0, 2);

        if (month.substr(0, 1) == '0') {
            month = month.substr(1, 1);
        }

        var year = inputNumbers.slice(-2);

        var thisyear = new Date().getFullYear().toString();
        var year = thisyear.substr(0, thisyear.length - 2) + inputNumbers.slice(-2);

        $('.ginput_card_expiration_month').val(month);
        $('.ginput_card_expiration_year').val(year);


    }
    else {
        $('.ginput_card_expiration_month').val('');
        $('.ginput_card_expiration_year').val('');
    }
}