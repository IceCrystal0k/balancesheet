//PLUGIN EXTENSION
(function ($) {
    var _this;
    var _options;
    $.fn.select2tree = function (options) {
        var defaults = {
            language: 'en-GB',
            theme: 'bootstrap5',
            width: '100%',
            selectionCssClass: ':all:',
            matcher: matchCustom,
            templateSelection: templateSelectionCustom,
            templateResult: templateResultCustom,
        };
        var opts = $.extend(defaults, options);
        _options = options;
        var $this = $(this);
        _this = $(this);
        $(this)
            .select2(opts)
            .on('select2:open', function () {
                open($this);
            });
    };

    function templateResultCustom(data, container) {
        if (data.element) {
            //insert span element and add 'parent' property
            var $wrapper = $('<span></span><span>' + data.text + '</span>');
            var $switchSpn = $wrapper.first();
            var $element = $(data.element);
            var $select = $element.parent();
            var $container = $(container);

            $container.attr('val', $element.val());
            $container.attr('data-parent', $element.data('parent'));

            var hasChilds = $select.find("option[data-parent='" + $element.val() + "']").length > 0;
            var isSearching = $('.select2-search__field').val().length > 0;

            if (isSearching) {
                $switchSpn.css({
                    padding: '0 10px 0 10px',
                });
            } else if (hasChilds) {
                $switchSpn.addClass('switch-tree bi');
                if (_options.toggleClass) {
                    $switchSpn.addClass(_options.toggleClass);
                }
                if ($switchSpn.hasClass('bi-chevron-right'))
                    $switchSpn.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                else $switchSpn.removeClass('bi-chevron-down').addClass('bi-chevron-right');

                $switchSpn.css({
                    padding: '0 10px 0 10px',
                    cursor: 'pointer',
                });
            }

            if (hasParent($element)) {
                var paddingLeft = getTreeLevel($select, $element.val()) * 2;
                if (!hasChilds) paddingLeft++;
                $container.css('margin-left', paddingLeft + 'em');
            }

            return $wrapper;
        } else {
            return data.text;
        }
    }

    function hasParent($element) {
        return $element.data('parent') !== '0';
    }

    function getTreeLevel($select, id) {
        var level = 0;
        while ($select.find("option[data-parent!='0'][value='" + id + "']").length > 0) {
            id = $select.find("option[value='" + id + "']").data('parent');
            level++;
        }
        return level;
    }

    function moveOption($select, id) {
        if (id) {
            $select
                .find(".select2-results__options li[data-parent='" + id + "']")
                .insertAfter('.select2-results__options li[val=' + id + ']');
            $select.find(".select2-results__options li[data-parent='" + id + "']").each(function () {
                moveOption($select, $(this).attr('val'));
            });
        } else {
            $(".select2-results__options li[data-parent!='0']").css('display', 'none');
            $(".select2-results__options li[data-parent='0']").appendTo('.select2-results__options ul');
            $(".select2-results__options li[data-parent='0']").each(function () {
                moveOption($select, $(this).attr('val'));
            });
        }
    }

    function switchAction($select, id, open) {
        var childs = $(".select2-results__options li[data-parent='" + id + "']");
        //expand childs.
        //childs.each(function() {
        //  switchAction($select, $(this).attr("val"), open);
        //});

        var parent = $('.select2-results__options li[val=' + id + '] span[class]:eq(0)');
        if (open) {
            parent.removeClass('bi-chevron-right').addClass('bi-chevron-down');
            childs.slideDown();
        } else {
            parent.removeClass('bi-chevron-down').addClass('bi-chevron-right');
            childs.slideUp();
        }
    }

    function handleExpand(ev) {
        switchAction(_this, $(this).parent().attr('val'), $(this).hasClass('bi-chevron-right'));
        ev.stopPropagation();
    }

    function handleCollapse(ev) {
        // setTimeout(function() {
        //     let filterParent = $('#kt-toolbar-filter');
        //     if (filterParent.length > 0) {
        //         filterParent.addClass('show');
        //     }
        // }, 50);

        // self.trigger('toggle', {
        //     originalEvent: evt
        // });

        return false;
    }

    function open($select) {
        setTimeout(function () {
            moveOption($select);
            //override mousedown for collapse/expand
            $('.switch-tree').off('mousedown', handleExpand);
            $('.switch-tree').on('mousedown', handleExpand);
            //override mouseup to nothing
            // $('.switch-tree').off('mouseup'); //, handleCollapse);
            $('.switch-tree').on('mouseup', handleCollapse);
        }, 0);
    }

    function matchCustom(params, data) {
        if (!params.term || params.term.trim() === '') {
            return data;
        }
        if (typeof data.text === 'undefined') {
            return null;
        }
        var term = params.term.toLowerCase();
        var $element = $(data.element);
        var $select = $element.parent();
        var childMatched = checkForChildMatch($select, $element, term);
        if (childMatched || data.text.toLowerCase().indexOf(term) >= 0) {
            $('#' + data._resultId).css('display', 'unset');
            return data;
        }
        return null;
    }

    function checkForChildMatch($select, $element, term) {
        var matched = false;
        var childs = $select.find('option[data-parent=' + $element.val() + ']');
        var childMatchFilter = jQuery.makeArray(childs).some((s) => s.text.toLowerCase().indexOf(term) >= 0);
        if (childMatchFilter) return true;

        childs.each(function () {
            var innerChild = checkForChildMatch($select, $(this), term);
            if (innerChild) matched = true;
        });

        return matched;
    }

    function templateSelectionCustom(item) {
        if (!item.id || item.id == '-1') {
            return $("<i class='fa fa-hand-o-right'></i><span> " + item.text + '</span>');
        }

        var $element = $(item.element);
        var $select = $element.parent();

        var parentsText = getParentText($select, $element);
        if (parentsText != '') parentsText += ' - ';

        var $state = $('<span> ' + parentsText + item.text + '</span>');
        return $state;
    }

    function getParentText($select, $element) {
        var text = '';
        var parentVal = $element.data('parent');
        if (parentVal == '0') return text;

        var parent = $select.find('option[value=' + parentVal + ']');

        if (parent) {
            text = getParentText($select, parent);
            if (text != '') text += ' - ';
            text += parent.text();
        }
        return text;
    }
})(jQuery);
