(function(a, b, c) {
    "use strict";
    var d = function(a, b) {
        this.$originalElement = a;
        this.options = b;
        this.metadata = this.$originalElement.data("sol-options")
    };
    d.prototype = {
        SOL_OPTION_FORMAT: {
            type: "option",
            value: undefined,
            selected: false,
            disabled: false,
            label: undefined,
            tooltip: undefined,
            cssClass: ""
        },
        SOL_OPTIONGROUP_FORMAT: {
            type: "optiongroup",
            label: undefined,
            tooltip: undefined,
            disabled: false,
            children: undefined
        },
        DATA_KEY: "sol-element",
        WINDOW_EVENTS_KEY: "sol-window-events",
        defaults: {
            data: undefined,
            name: undefined,
            texts: {
                noItemsAvailable: toroflixPublic.noItemsAvailable,
                selectAll: toroflixPublic.selectAll,
                selectNone: toroflixPublic.selectNone,
                quickDelete: "&times;",
                searchplaceholder: toroflixPublic.searchplaceholder,
                loadingData: toroflixPublic.loadingData,
                itemsSelected: "{$a} items selected"
            },
            events: {
                onInitialized: undefined,
                onRendered: undefined,
                onOpen: undefined,
                onClose: undefined,
                onChange: undefined,
                onScroll: function() {
                    var a = this.$input.offset().top - this.config.scrollTarget.scrollTop() + this.$input.outerHeight(false),
                        b = this.$selectionContainer.outerHeight(false),
                        d = a + b,
                        e = this.config.displayContainerAboveInput || c.documentElement.clientHeight - this.config.scrollTarget.scrollTop() < d,
                        f = this.$innerContainer.outerWidth(false) - parseInt(this.$selectionContainer.css("border-left-width"), 10) - parseInt(this.$selectionContainer.css("border-right-width"), 10);
                    if (e) {
                        a = this.$input.offset().top - b - this.config.scrollTarget.scrollTop() + parseInt(this.$selectionContainer.css("border-bottom-width"), 10);
                        this.$container.removeClass("sol-selection-bottom").addClass("sol-selection-top")
                    } else this.$container.removeClass("sol-selection-top").addClass("sol-selection-bottom");
                    if (this.$innerContainer.css("display") !== "block") f = f * 1.2;
                    else {
                        var g = e ? "border-bottom-right-radius" : "border-top-right-radius";
                        this.$selectionContainer.css(g, "initial");
                        if (this.$actionButtons) this.$actionButtons.css(g, "initial")
                    }
                    this.$selectionContainer.css("top", Math.floor(a)).css("left", Math.floor(this.$container.offset().left)).css("width", f);
                    this.config.displayContainerAboveInput = e
                }
            },
            selectAllMaxItemsThreshold: 30,
            showSelectAll: function() {
                return this.config.multiple && this.config.selectAllMaxItemsThreshold && this.items && this.items.length <= this.config.selectAllMaxItemsThreshold
            },
            useBracketParameters: false,
            multiple: undefined,
            showSelectionBelowList: false,
            allowNullSelection: false,
            scrollTarget: undefined,
            maxHeight: undefined,
            converter: undefined,
            asyncBatchSize: 300,
            maxShow: 0
        },
        init: function() {
            this.config = a.extend(true, {}, this.defaults, this.options, this.metadata);
            var c = this._getNameAttribute(),
                d = this;
            if (!c) {
                this._showErrorLabel("name attribute is required");
                return
            }
            if (typeof String.prototype.trim !== "function") String.prototype.trim = function() {
                return this.replace(/^\s+|\s+$/g, "")
            };
            this.config.multiple = this.config.multiple || this.$originalElement.attr("multiple");
            if (!this.config.scrollTarget) this.config.scrollTarget = a(b);
            this._registerWindowEventsIfNeccessary();
            this._initializeUiElements();
            this._initializeInputEvents();
            setTimeout(function() {
                d._initializeData();
                d.$originalElement.data(d.DATA_KEY, d).removeAttr("name").data("sol-name", c)
            }, 0);
            this.$originalElement.hide();
            this.$container.css("visibility", "initial").show();
            return this
        },
        _getNameAttribute: function() {
            return this.config.name || this.$originalElement.data("sol-name") || this.$originalElement.attr("name")
        },
        _showErrorLabel: function(b) {
            var c = a('<div style="color: red; font-weight: bold;" />').html(b);
            if (!this.$container) c.insertAfter(this.$originalElement);
            else this.$container.append(c)
        },
        _registerWindowEventsIfNeccessary: function() {
            if (!b[this.WINDOW_EVENTS_KEY]) {
                a(c).click(function(b) {
                    var c = a(b.target),
                        e = c.closest(".sol-selection-container"),
                        f = c.closest(".sol-inner-container"),
                        g;
                    if (f.length) g = f.first().parent(".sol-container");
                    else if (e.length) g = e.first().parent(".sol-container");
                    a(".sol-active").not(g).each(function(b, c) {
                        a(c).data(d.prototype.DATA_KEY).close()
                    })
                });
                b[this.WINDOW_EVENTS_KEY] = true
            }
        },
        _initializeUiElements: function() {
            var b = this;
            this.internalScrollWrapper = function() {
                if (a.isFunction(b.config.events.onScroll)) b.config.events.onScroll.call(b)
            };
            this.$input = a('<input type="text"/>').attr("placeholder", this.config.texts.searchplaceholder);
            this.$noResultsItem = a('<div class="sol-no-results"/>').html(this.config.texts.noItemsAvailable).hide();
            this.$loadingData = a('<div class="sol-loading-data"/>').html(this.config.texts.loadingData);
            this.$xItemsSelected = a('<div class="sol-results-count"/>');
            this.$caret = a('<div class="sol-caret-container"><b class="sol-caret"/></div>').click(function(a) {
                b.toggle();
                a.preventDefault();
                return false
            });
            var c = a('<div class="sol-input-container"/>').append(this.$input);
            this.$innerContainer = a('<div class="sol-inner-container"/>').append(c).append(this.$caret);
            this.$selection = a('<div class="sol-selection"/>');
            this.$selectionContainer = a('<div class="sol-selection-container"/>').append(this.$noResultsItem).append(this.$loadingData).append(this.$selection);
            this.$container = a('<div class="sol-container"/>').hide().data(this.DATA_KEY, this).append(this.$selectionContainer).append(this.$innerContainer).insertBefore(this.$originalElement);
            this.$showSelectionContainer = a('<div class="sol-current-selection"/>');
            if (this.config.showSelectionBelowList) this.$showSelectionContainer.insertAfter(this.$innerContainer);
            else this.$showSelectionContainer.insertBefore(this.$innerContainer);
            if (this.config.maxHeight) this.$selection.css("max-height", this.config.maxHeight);
            var d = this.$originalElement.attr("class"),
                e = this.$originalElement.attr("style"),
                f = [],
                g = [];
            if (d && d.length > 0) {
                f = d.split(/\s+/);
                for (var h = 0; h < f.length; h++) this.$container.addClass(f[h])
            }
            if (e && e.length > 0) {
                g = e.split(/\;/);
                for (var h = 0; h < g.length; h++) {
                    var i = g[h].split(/\s*\:\s*/g);
                    if (i.length === 2)
                        if (i[0].toLowerCase().indexOf("height") >= 0) this.$innerContainer.css(i[0].trim(), i[1].trim());
                        else this.$container.css(i[0].trim(), i[1].trim())
                }
            }
            if (this.$originalElement.css("display") !== "block") this.$container.css("width", this._getActualCssPropertyValue(this.$originalElement, "width"));
            if (a.isFunction(this.config.events.onRendered)) this.config.events.onRendered.call(this, this)
        },
        _getActualCssPropertyValue: function(a, d) {
            var e = a.get(0),
                f = a.css("display");
            a.css("display", "none");
            if (e.currentStyle) return e.currentStyle[d];
            else if (b.getComputedStyle) return c.defaultView.getComputedStyle(e, null).getPropertyValue(d);
            a.css("display", f);
            return a.css(d)
        },
        _initializeInputEvents: function() {
            var b = this,
                c = this.$input.parents("form").first();
            if (c && c.length === 1 && !c.data(this.WINDOW_EVENTS_KEY)) {
                var d = function() {
                    var d = [];
                    c.find(".sol-option input").each(function(b, c) {
                        var e = a(c),
                            f = e.data("sol-item").selected;
                        if (e.prop("checked") !== f) {
                            e.prop("checked", f).trigger("sol-change", true);
                            d.push(e)
                        }
                    });
                    if (d.length > 0 && a.isFunction(b.config.events.onChange)) b.config.events.onChange.call(b, b, d)
                };
                c.on("reset", function(a) {
                    d.call(b);
                    setTimeout(function() {
                        d.call(b)
                    }, 100)
                });
                c.data(this.WINDOW_EVENTS_KEY, true)
            }
            this.$input.focus(function() {
                b.open()
            }).on("propertychange input", function(a) {
                var c = true;
                if (a.type == "propertychange") c = a.originalEvent.propertyName.toLowerCase() == "value";
                if (c) b._applySearchTermFilter()
            });
            this.$container.on("keydown", function(c) {
                var d = c.keyCode;
                if (!b.$noResultsItem.is(":visible")) {
                    var e, f, g, h = false,
                        i = b.$selection.find(".sol-option:visible");
                    if (d === 40 || d === 38) {
                        b._setKeyBoardNavigationMode(true);
                        e = b.$selection.find(".sol-option.keyboard-selection");
                        g = d === 38 ? -1 : 1;
                        var j = i.index(e) + g;
                        if (j < 0) j = i.length - 1;
                        else if (j >= i.length) j = 0;
                        e.removeClass("keyboard-selection");
                        f = a(i[j]).addClass("keyboard-selection");
                        b.$selection.scrollTop(b.$selection.scrollTop() + f.position().top);
                        h = true
                    } else if (b.keyboardNavigationMode === true && d === 32) {
                        e = b.$selection.find(".sol-option.keyboard-selection input");
                        e.prop("checked", !e.prop("checked")).trigger("change");
                        h = true
                    }
                    if (h) {
                        c.preventDefault();
                        return false
                    }
                }
            }).on("keyup", function(a) {
                var c = a.keyCode;
                if (c === 27)
                    if (b.keyboardNavigationMode === true) b._setKeyBoardNavigationMode(false);
                    else if (b.$input.val() === "") {
                    b.$caret.trigger("click");
                    b.$input.trigger("blur")
                } else b.$input.val("").trigger("input");
                else if (c === 16 || c === 17 || c === 18 || c === 20) return
            })
        },
        _setKeyBoardNavigationMode: function(a) {
            if (a) {
                this.keyboardNavigationMode = true;
                this.$selection.addClass("sol-keyboard-navigation")
            } else {
                this.keyboardNavigationMode = false;
                this.$selection.find(".sol-option.keyboard-selection");
                this.$selection.removeClass("sol-keyboard-navigation");
                this.$selectionContainer.find(".sol-option.keyboard-selection").removeClass("keyboard-selection");
                this.$selection.scrollTop(0)
            }
        },
        _applySearchTermFilter: function() {
            if (!this.items || this.items.length === 0) return;
            var b = this.$input.val(),
                c = (b || "").toLowerCase();
            this.$selectionContainer.find(".sol-filtered-search").removeClass("sol-filtered-search");
            this._setNoResultsItemVisible(false);
            if (c.trim().length > 0) this._findTerms(this.items, c);
            if (a.isFunction(this.config.events.onScroll)) this.config.events.onScroll.call(this)
        },
        _findTerms: function(b, c) {
            if (!b || !a.isArray(b) || b.length === 0) return;
            var d = this;
            this._setKeyBoardNavigationMode(false);
            a.each(b, function(a, b) {
                if (b.type === "option") {
                    var e = b.displayElement,
                        f = (b.label + " " + b.tooltip).trim().toLowerCase();
                    if (f.indexOf(c) === -1) e.addClass("sol-filtered-search")
                } else {
                    d._findTerms(b.children, c);
                    var g = b.displayElement.find(".sol-option:not(.sol-filtered-search)");
                    if (g.length === 0) b.displayElement.addClass("sol-filtered-search")
                }
            });
            this._setNoResultsItemVisible(this.$selectionContainer.find(".sol-option:not(.sol-filtered-search)").length === 0)
        },
        _initializeData: function() {
            if (!this.config.data) this.items = this._detectDataFromOriginalElement();
            else if (a.isFunction(this.config.data)) this.items = this._fetchDataFromFunction(this.config.data);
            else if (a.isArray(this.config.data)) this.items = this._fetchDataFromArray(this.config.data);
            else if (typeof this.config.data === typeof "a string") this._loadItemsFromUrl(this.config.data);
            else this._showErrorLabel("Unknown data type");
            if (this.items) this._processDataItems(this.items)
        },
        _detectDataFromOriginalElement: function() {
            if (this.$originalElement.prop("tagName").toLowerCase() === "select") {
                var b = this,
                    c = [];
                a.each(this.$originalElement.children(), function(d, e) {
                    var f = a(e),
                        g = f.prop("tagName").toLowerCase(),
                        h;
                    if (g === "option") {
                        h = b._processSelectOption(f);
                        if (h) c.push(h)
                    } else if (g === "optgroup") {
                        h = b._processSelectOptgroup(f);
                        if (h) c.push(h)
                    } else b._showErrorLabel("Invalid element found in select: " + g + ". Only option and optgroup are allowed")
                });
                return this._invokeConverterIfNeccessary(c)
            } else if (this.$originalElement.data("sol-data")) {
                var d = this.$originalElement.data("sol-data");
                return this._invokeConverterIfNeccessary(d)
            } else this._showErrorLabel('Could not determine data from original element. Must be a select or data must be provided as data-sol-data="" attribute')
        },
        _processSelectOption: function(b) {
            return a.extend({}, this.SOL_OPTION_FORMAT, {
                value: b.val(),
                selected: b.prop("selected"),
                disabled: b.prop("disabled"),
                cssClass: b.attr("class"),
                label: b.html(),
                tooltip: b.attr("title"),
                element: b
            })
        },
        _processSelectOptgroup: function(b) {
            var c = this,
                d = a.extend({}, this.SOL_OPTIONGROUP_FORMAT, {
                    label: b.attr("label"),
                    tooltip: b.attr("title"),
                    disabled: b.prop("disabled"),
                    children: []
                }),
                e = b.children("option");
            a.each(e, function(b, e) {
                var f = a(e),
                    g = c._processSelectOption(f);
                if (d.disabled) g.disabled = true;
                d.children.push(g)
            });
            return d
        },
        _fetchDataFromFunction: function(a) {
            return this._invokeConverterIfNeccessary(a(this))
        },
        _fetchDataFromArray: function(a) {
            return this._invokeConverterIfNeccessary(a)
        },
        _loadItemsFromUrl: function(b) {
            var c = this;
            a.ajax(b, {
                success: function(a) {
                    c.items = c._invokeConverterIfNeccessary(a);
                    if (c.items) c._processDataItems(c.items)
                },
                error: function(a, d, e) {
                    c._showErrorLabel("Error loading from url " + b + ": " + e)
                },
                dataType: "json"
            })
        },
        _invokeConverterIfNeccessary: function(b) {
            if (a.isFunction(this.config.converter)) return this.config.converter.call(this, this, b);
            return b
        },
        _processDataItems: function(b) {
            if (!b) {
                this._showErrorLabel("Data items not present. Maybe the converter did not return any values");
                return
            }
            if (b.length === 0) {
                this._setNoResultsItemVisible(true);
                this.$loadingData.remove();
                return
            }
            var c = this,
                d = 0,
                e = function() {
                    this.$loadingData.remove();
                    this._initializeSelectAll();
                    if (a.isFunction(this.config.events.onInitialized)) this.config.events.onInitialized.call(this, this, b)
                },
                f = function() {
                    var a = 0,
                        g;
                    while (a++ < c.config.asyncBatchSize && d < b.length) {
                        g = b[d++];
                        if (g.type === c.SOL_OPTION_FORMAT.type) c._renderOption(g);
                        else if (g.type === c.SOL_OPTIONGROUP_FORMAT.type) c._renderOptiongroup(g);
                        else {
                            c._showErrorLabel("Invalid item type found " + g.type);
                            return
                        }
                    }
                    if (d >= b.length) e.call(c);
                    else setTimeout(f, 0)
                };
            f.call(this)
        },
        _renderOption: function(b, c) {
            var d = this,
                e = c || this.$selection,
                f, g = a('<div class="sol-label-text"/>').html(b.label.trim().length === 0 ? "&nbsp;" : b.label).addClass(b.cssClass),
                h, i, j = this._getNameAttribute();
            if (this.config.multiple) {
                f = a('<input type="checkbox" class="sol-checkbox"/>');
                if (this.config.useBracketParameters) j += "[]"
            } else f = a('<input type="radio" class="sol-radio"/>').on("change", function() {
                d.$selectionContainer.find('input[type="radio"][name="' + j + '"]').not(a(this)).trigger("sol-deselect")
            }).on("sol-deselect", function() {
                d._removeSelectionDisplayItem(a(this))
            });
            f.on("change", function(b, c) {
                a(this).trigger("sol-change", c)
            }).on("sol-change", function(b, c) {
                d._selectionChange(a(this), c)
            }).data("sol-item", b).prop("checked", b.selected).prop("disabled", b.disabled).attr("name", j).val(b.value);
            h = a('<label class="sol-label"/>').attr("title", b.tooltip).append(f).append(g);
            i = a('<div class="sol-option"/>').append(h);
            b.displayElement = i;
            e.append(i);
            if (b.selected) this._addSelectionDisplayItem(f)
        },
        _renderOptiongroup: function(b) {
            var c = this,
                d = a('<div class="sol-optiongroup-label"/>').attr("title", b.tooltip).html(b.label),
                e = a('<div class="sol-optiongroup"/>').append(d);
            if (b.disabled) e.addClass("disabled");
            if (a.isArray(b.children)) a.each(b.children, function(a, b) {
                c._renderOption(b, e)
            });
            b.displayElement = e;
            this.$selection.append(e)
        },
        _initializeSelectAll: function() {
            if (this.config.showSelectAll === true || a.isFunction(this.config.showSelectAll) && this.config.showSelectAll.call(this)) {
                var b = this,
                    c = a('<a href="#" class="sol-deselect-all"/>').html(this.config.texts.selectNone).click(function(a) {
                        b.deselectAll();
                        a.preventDefault();
                        return false
                    }),
                    d = a('<a href="#" class="sol-select-all"/>').html(this.config.texts.selectAll).click(function(a) {
                        b.selectAll();
                        a.preventDefault();
                        return false
                    });
                this.$actionButtons = a('<div class="sol-action-buttons"/>').append(d).append(c).append('<div class="sol-clearfix"/>');
                this.$selectionContainer.prepend(this.$actionButtons)
            }
        },
        _selectionChange: function(b, c) {
            if (this.$originalElement && this.$originalElement.prop("tagName").toLowerCase() === "select") {
                var d = this;
                this.$originalElement.find("option").each(function(c, e) {
                    var f = a(e);
                    if (f.val() === b.val()) {
                        f.prop("selected", b.prop("checked"));
                        d.$originalElement.trigger("change");
                        return
                    }
                })
            }
            if (b.prop("checked")) this._addSelectionDisplayItem(b);
            else this._removeSelectionDisplayItem(b);
            if (this.config.multiple) this.config.scrollTarget.trigger("scroll");
            else this.close();
            var e = this.$showSelectionContainer.children(".sol-selected-display-item");
            if (this.config.maxShow != 0 && e.length > this.config.maxShow) {
                e.hide();
                var f = this.config.texts.itemsSelected.replace("{$a}", e.length);
                this.$xItemsSelected.html('<div class="sol-selected-display-item-text">' + f + "<div>");
                this.$showSelectionContainer.append(this.$xItemsSelected);
                this.$xItemsSelected.show()
            } else {
                e.show();
                this.$xItemsSelected.hide()
            }
            if (!c && a.isFunction(this.config.events.onChange)) this.config.events.onChange.call(this, this, b);
            this.$container.removeClass("sol-active");
        },
        _addSelectionDisplayItem: function(b) {
            var c = b.data("sol-item"),
                d = c.displaySelectionItem,
                e;
            if (!d) {
                e = a('<span class="sol-selected-display-item-text" />').html(c.label);
                d = a('<div class="sol-selected-display-item"/>').append(e).attr("title", c.tooltip).appendTo(this.$showSelectionContainer);
                if ((this.config.multiple || this.config.allowNullSelection) && !b.prop("disabled")) a('<span class="sol-quick-delete"/>').html(this.config.texts.quickDelete).click(function() {
                    b.prop("checked", false).trigger("change")
                }).prependTo(d);
                c.displaySelectionItem = d
            }
        },
        _removeSelectionDisplayItem: function(a) {
            var b = a.data("sol-item"),
                c = b.displaySelectionItem;
            if (c) {
                c.remove();
                b.displaySelectionItem = undefined
            }
        },
        _setNoResultsItemVisible: function(a) {
            if (a) {
                this.$noResultsItem.show();
                this.$selection.hide();
                if (this.$actionButtons) this.$actionButtons.hide()
            } else {
                this.$noResultsItem.hide();
                this.$selection.show();
                if (this.$actionButtons) this.$actionButtons.show()
            }
        },
        isOpen: function() {
            return this.$container.hasClass("sol-active")
        },
        isClosed: function() {
            return !this.isOpen()
        },
        toggle: function() {
            if (this.isOpen()) this.close();
            else this.open()
        },
        open: function() {
            if (this.isClosed()) {
                this.$container.addClass("sol-active");
                this.config.scrollTarget.bind("scroll", this.internalScrollWrapper).trigger("scroll");
                a(b).on("resize", this.internalScrollWrapper);
                if (a.isFunction(this.config.events.onOpen)) this.config.events.onOpen.call(this, this)
            }
        },
        close: function() {
            if (this.isOpen()) {
                this._setKeyBoardNavigationMode(false);
                this.$container.removeClass("sol-active");
                this.config.scrollTarget.unbind("scroll", this.internalScrollWrapper);
                a(b).off("resize");
                this.$input.val("");
                this._applySearchTermFilter();
                this.config.displayContainerAboveInput = undefined;
                if (a.isFunction(this.config.events.onClose)) this.config.events.onClose.call(this, this)
            }
        },
        selectAll: function() {
            if (this.config.multiple) {
                var b = this.$selectionContainer.find('input[type="checkbox"]:not([disabled], :checked)').prop("checked", true).trigger("change", true);
                this.close();
                if (a.isFunction(this.config.events.onChange)) this.config.events.onChange.call(this, this, b)
            }
        },
        deselectAll: function() {
            if (this.config.multiple) {
                var b = this.$selectionContainer.find('input[type="checkbox"]:not([disabled]):checked').prop("checked", false).trigger("change", true);
                this.close();
                if (a.isFunction(this.config.events.onChange)) this.config.events.onChange.call(this, this, b)
            }
        },
        getSelection: function() {
            return this.$selection.find("input:checked");

        }
    };
    d.defaults = d.prototype.defaults;
    b.SearchableOptionList = d;
    a.fn.searchableOptionList = function(b) {
        var c = [];
        this.each(function() {
            var e = a(this),
                f = e.data(d.prototype.DATA_KEY);
            if (f) c.push(f);
            else {
                var g = new d(e, b);
                c.push(g);
                setTimeout(function() {
                    g.init()
                }, 0)
            }
        });
        if (c.length === 1) return c[0];
        return c
    }
})(jQuery, window, document);
$(document).ready(function() {
    $(".Select-Md").searchableOptionList({
        showSelectAll: false,
        texts: {
            noItemsAvailable: "No se encontro",
            quickDelete: "&#xE5CD;"
        }
    })
})