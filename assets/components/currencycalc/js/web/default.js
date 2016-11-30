(function () {
    function CurrencyCalc(options) {
        //
        ['data'].forEach(function (val, i, arr) {
            if (typeof(options[val]) == 'undefined' || options[val] == '') {
                console.error('[CurrencyCalc] Bad config', arr);
                return;
            }
        });

        //
        var self = this;
        self.initialized = false;
        self.running = false;

        /**
         * Инициализирует класс.
         * @returns {boolean}
         */
        self.initialize = function (options) {
            if (!self.initialized) {
                //
                self.config = {
                    data: [],
                };
                self.selectors = {
                    item: '.js-cc-item',
                    input: '.js-cc-input',
                };
                // self.elements = {};

                //
                Object.keys(options).forEach(function (key) {
                    if (['selectors'].indexOf(key) !== -1) {
                        return;
                    }
                    self.config[key] = options[key];
                });

                //
                ['selectors'].forEach(function (key) {
                    if (options[key]) {
                        Object.keys(options[key]).forEach(function (i) {
                            self.selectors[i] = options.selectors[i];
                        });
                    }
                });

                //
                // ['form', 'content'].forEach(function (key) {
                //     self.elements[key] = $(self.selectors['wrapper']).find(self.selectors[key]);
                // });
            }
            self.initialized = true;

            return self.initialized;
        };

        /**
         * Запускает основные действия.
         * @returns {boolean}
         */
        self.run = function () {
            if (self.initialized && !self.running) {
                // При изменении поля
                $(document).on('input', self.selectors['input'], function (e) {
                    var $input = $(this);
                    var $item = $input.closest(self.selectors['item']);
                    var id = $item.data('cc-id');
                    var type = $input.data('cc-input-type');
                    var value = parseFloat($input.val());
                    if (isNaN(value)) {
                        value = 0;
                    }

                    if (self.config.data[id]) {
                        var rate = self.config.data[id]['rate'];

                        if (type == 'from') {
                            var number = value * rate;
                        } else {
                            var number = value / rate;
                        }

                        $item.find(self.selectors['input'])
                            .filter('[data-cc-input-type="' + (type == 'from' ? 'to' : 'from') + '"]')
                            .val(number);
                    }

                });
            }
            self.running = true;

            return self.running;
        };

        // Initialize && Run!
        if (self.initialize(options)) {
            self.run();
        }
    }

    window.CurrencyCalc = CurrencyCalc;
})();