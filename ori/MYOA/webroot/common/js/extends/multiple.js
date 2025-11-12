/**
 @ Name：layui.multiSelect 多选下拉框
 @ Author：呆鸽
 @ Date: 2018-11-05 19:48:06
 @ Version: 1.0
 @ License：MIT
 @ Remark:
 本组件基于layui官方源码 form.js 修改，
 参考了 狠狠亲一口 的插件 https://fly.layui.com/extend/multiSelect/
 由于该作者的插件无法使用事件绑定功能，因此重新改了一个，增加绑定事件功能(功能正常)，键盘选择功能(功能正常)，搜索功能(目前存在bug)
 若您在使用过程中发现了bug或者找到了bug的产生原因，欢迎来fly社区反馈

使用教程：
    1. 在您需要渲染为多选下拉框的 select 元素上增加 multiple 属性
    2. 和 layui 其他扩展组件一样，使用 layui.extend 方法配置文件路径，然后再 use 一下即可
css样式
--------------
<style type="text/css">
    .layui-this .layui-form-checked[lay-skin=primary] i{
        border: 1px solid #fff;
        font-weight: bold;
    }
    .layui-this .layui-form-checkbox[lay-skin=primary] span{
        color: #fff;
    }
</style>

html代码
--------------
<select id="weekday" lay-filter="weekday" multiple>
    <option value="1">星期一</option>
    <option value="2">星期二</option>
    <option value="3">星期三</option>
    <option value="4">星期四</option>
    <option value="5">星期五</option>
    <option value="6">星期六</option>
    <option value="7">星期日</option>
</select>

JavaScript代码
---------------
layui.extend({
    //路径指向本js文件即可
    multiSelect: '${pageContext.request.contextPath}/jslib/layui/lay/multiple'
}).use(['multiSelect'], function () {
    var $ = layui.jquery,
        multiSelect = layui.multiSelect;

    1. 绑定事件(与layui.form的绑定事件方法一致)
    multiSelect.on('mselect(weekday)', function (data) {
        //渲染后的元素
        console.log(data.elem)
        //已选中的值（数组格式）
        console.log(data.value)
        //已选中的文本（数组格式）
        console.log(data.text)
    });

    2. 手动重新渲染（由于layui.form.render会渲染成单选下拉框，若要保持多选下拉，需要使用 multiSelect.render() 重新渲染 select 元素）
    $('#weekday').find("option:first").before("<option value='8'>星期八</option>");
    multiSelect.render();

    3. 获取选中的值（从 values 属性中获取）
    $('#weekday').attr('values');
});
 */
layui.define('jquery', function (exports) { //该组件依赖 jquery
    //字符常量
    var $ = layui.$
        , MOD_NAME = 'multiSelect', ELEM = '[multiple]', SELECTED = 'layui-selected', THIS = 'layui-this'
        , SHOW = 'layui-show', HIDE = 'layui-hide', DISABLED = 'layui-disabled',
        TIPS = '请选择', CLASS = 'layui-form-select', TITLE = 'layui-select-title',
        CHECK_CLASS = 'layui-form-checkbox', CHECKED_THIS = 'layui-form-checked'
        , NONE = 'layui-select-none',
        MultiSelect = function (options) {
            this.config = options ? options : $.extend(MultiSelect.prototype.config, options);
        };

    MultiSelect.prototype.set = function (options) {
        var that = this;
        $.extend(true, that.config, options);
        return that;
    };
    MultiSelect.prototype.on = function (events, callback) {
        return layui.onevent.call(this, MOD_NAME, events, callback);
    };

    MultiSelect.prototype.val = function (filter, object) {
        var that = this, elemForm = $(ELEM + function () {
            return filter ? ('[lay-filter="' + filter + '"]') : '';
        }());

        elemForm.each(function (index, item) {
            var itemFrom = $(this);
            layui.each(object, function (key, value) {
                var itemElem = itemFrom.find('[name="' + key + '"]');
                var values = value ? value.split(',') : [];
                if (value instanceof Array) {
                    itemElem.attr('values', value.join(','));
                } else {
                    itemElem.attr('values',values.join(','));
                }
            });
        });
        MultiSelect.render(filter);
    };

    //默认配置
    MultiSelect.prototype.config = {};

    //表单控件渲染
    MultiSelect.prototype.render = function (filter) {
        var that = this, elemForm = $(ELEM + function () {
            return filter ? ('[lay-filter="' + filter + '"]') : '';
        }());
        var initValue = '', thatInput,
            mselect = elemForm

            //隐藏 select
            , hide = function (e, clear) {
                if (!$(e.target).parent().hasClass(TITLE) || clear) {
                    $('.' + CLASS).removeClass(CLASS + 'ed ' + CLASS + 'up');
                    thatInput && initValue && thatInput.attr('values',initValue);
                }

                thatInput = null;
            }

            //各种事件
            , events = function (reElem, disabled, isSearch) {
                var select = $(this)
                    , title = reElem.find('.' + TITLE)
                    , input = title.find('input') //选中的值名称
                    , dl = reElem.find('dl')
                    , dds = dl.children('dd')
                    , checks = dl.find('input[type=checkbox]') //选中的值
                    , values = select.attr('values') ? select.attr('values').split(',') : []//当前选中的值
                    , nearElem //select 组件当前选中的附近元素，用于辅助快捷键功能
                    ,$dom = $(document), $win = $(window);


                if (disabled) return;

                //展开下拉
                var showDown = function () {
                        var top = reElem.offset().top + reElem.outerHeight() + 5 - $win.scrollTop()
                            , dlHeight = dl.outerHeight();
                        reElem.addClass(CLASS + 'ed');
                        dds.removeClass(HIDE);
                        nearElem = null;

                        //初始选中样式
                        dds.removeClass(SELECTED);
                        select.children('option').prop('selected', false);
                        dds.each(function () {
                            var dd = $(this), value = dd.attr('lay-value'), check = dd.find("input[type=checkbox]"),
                                checkEle = dd.find(CHECK_CLASS), option = select.children('[value="' + value + '"]');
                            if ($.inArray(value, values) != -1) {
                                if (!check[0].disabled) {
                                    check[0].checked = true;
                                    check.addClass(CHECKED_THIS);
                                    option.prop('selected', true);
                                }
                                dd.addClass(SELECTED);
                            }
                        });
                        if(!dds.find('.'+SELECTED).length){
                            dds.find('.'+SELECTED).filter(':first').addClass(THIS).siblings().removeClass(THIS);
                        }

                        //上下定位识别
                        if (top + dlHeight > $win.height() && top >= dlHeight) {
                            reElem.addClass(CLASS + 'up');
                        }

                        followScroll();
                    }

                    //隐藏下拉
                    , hideDown = function (choose) {
                        reElem.removeClass(CLASS + 'ed ' + CLASS + 'up');
                        input.blur();
                        nearElem = null;

                        if (choose) return;

                        notOption(input.val(), function (none) {
                            var values = select.attr('values') ? select.attr('values').split(',') : [];

                            //未查询到相关值
                            if (none) {
                                var values = [], titles = [];
                                checks.filter(":checked").each(function () {
                                    values.push(this.value);
                                });
                                dds.each(function () {
                                    var dd = $(this), value = dd.attr('lay-value'), check = dd.find("input[type=checkbox]"),
                                        checkEle = dd.find(CHECK_CLASS);
                                    if ($.inArray(value, values) != -1) {
                                        titles.push(dd.text());
                                    }
                                });

                                initValue = titles.join(','); //重新获得初始选中值

                                //如果有选中值，则将输入框纠正为该值。否则清空输入框
                                input.val(initValue || '');
                            }
                        });
                    }

                    //定位下拉滚动条
                    , followScroll = function () {
                        var thisDd = dl.children('dd.' + THIS);

                        if (!thisDd[0]) return;

                        var posTop = thisDd.position().top
                            , dlHeight = dl.height()
                            , ddHeight = thisDd.height();

                        //若选中元素在滚动条不可见底部
                        if (posTop > dlHeight) {
                            dl.scrollTop(posTop + dl.scrollTop() - dlHeight + ddHeight - 5);
                        }

                        //若选择元素在滚动条不可见顶部
                        if (posTop < 0) {
                            dl.scrollTop(posTop + dl.scrollTop() - 5);
                        }
                    };

                //点击标题区域
                title.on('click', function (e) {
                    reElem.hasClass(CLASS + 'ed') ? (
                        hideDown()
                    ) : (
                        hide(e, true),
                            showDown()
                    );
                    dl.find('.' + NONE).remove();
                });

                //点击箭头获取焦点
                title.find('.layui-edge').on('click', function () {
                    input.focus();
                });

                //select 中 input 键盘事件
                input.on('keyup', function (e) { //键盘松开
                    var keyCode = e.keyCode;

                    //Tab键展开
                    if (keyCode === 9) {
                        showDown();
                    }
                }).on('keydown', function (e) { //键盘按下
                    var keyCode = e.keyCode;

                    //Tab键/ESC键隐藏
                    if (keyCode === 9 || keyCode === 27) {
                        hideDown();
                    }

                    //标注 dd 的选中状态
                    var setThisDd = function (prevNext, thisElem1) {
                        var nearDd, cacheNearElem;
                        e.preventDefault();

                        //得到当前队列元素
                        var thisElem = function () {
                            var thisDd = dl.children('dd.' + THIS);

                            //如果是搜索状态，且按 Down 键，且当前可视 dd 元素在选中元素之前，
                            //则将当前可视 dd 元素的上一个元素作为虚拟的当前选中元素，以保证递归不中断
                            if (dl.children('dd.' + HIDE)[0] && prevNext === 'next') {
                                var showDd = dl.children('dd:not(.' + HIDE + ',.' + DISABLED + ')')
                                    , firstIndex = showDd.eq(0).index();
                                if (firstIndex >= 0 && firstIndex < thisDd.index() && !showDd.hasClass(THIS)) {
                                    return showDd.eq(0).prev()[0] ? showDd.eq(0).prev() : dl.children(':last');
                                }
                            }

                            if (thisElem1 && thisElem1[0]) {
                                return thisElem1;
                            }
                            if (nearElem && nearElem[0]) {
                                return nearElem;
                            }

                            return thisDd;
                        }();

                        cacheNearElem = thisElem[prevNext](); //当前元素的附近元素
                        nearDd = thisElem[prevNext]('dd:not(.' + HIDE + ')'); //当前可视元素的 dd 元素

                        //如果附近的元素不存在，判断是否为第一个元素或者最后一个元素，其他情况则停止执行，并清空 nearElem
                        if (!cacheNearElem[0]) {
                            var dds = dl.children('dd:not(.' + HIDE + ')');
                            if(thisElem.is(dds.filter(":first"))){
                                nearElem = nearDd;
                                nearDd = dl.children('dd:not(.' + HIDE + ')').filter(':last');
                            }else if(thisElem.is(dds.filter(":last"))){
                                nearElem = nearDd;
                                nearDd = dl.children('dd:not(.' + HIDE + ')').filter(':first');
                            }else{
                                if(prevNext=='prev'){
                                    nearElem = dl.children('dd:not(.' + HIDE + ')').filter(':last');
                                    nearDd = dl.children('dd:not(.' + HIDE + ')').filter(':last');
                                }else if(prevNext=='next'){
                                    nearElem = dl.children('dd:not(.' + HIDE + ')').filter(':first');
                                    nearDd = dl.children('dd:not(.' + HIDE + ')').filter(':first');
                                }else{
                                    return nearElem = null;
                                }
                            }
                        }else{
                            //记录附近的元素，让其成为下一个当前元素
                            nearElem = thisElem[prevNext]();
                        }

                        //如果附近不是 dd ，或者附近的 dd 元素是禁用状态，则进入递归查找
                        if ((!nearDd[0] || nearDd.hasClass(DISABLED)) && nearElem[0]) {
                            return setThisDd(prevNext, nearElem);
                        }

                        nearDd.addClass(THIS).siblings().removeClass(THIS); //标注样式
                        followScroll(); //定位滚动条
                    };
                    var thisElem = dl.children('dd.' + THIS);

                    if (keyCode === 38) { //Up 键
                        setThisDd('prev');
                    }
                    if (keyCode === 40) { //Down 键
                        setThisDd('next');
                    }

                    //Enter 键
                    if (keyCode === 13) {
                        e.preventDefault();
                        dl.children('dd.' + THIS).trigger('click');
                    }
                });

                //检测值是否不属于 select 项
                var notOption = function (value, callback, origin) {
                    var num = 0;
                    layui.each(dds, function () {
                        var othis = $(this)
                            , text = othis.text()
                            , not = text.indexOf(value) === -1;
                        if (value === '' || (origin === 'blur') ? value !== text : not) num++;
                        origin === 'keyup' && othis[not ? 'addClass' : 'removeClass'](HIDE);
                    });
                    var none = num === dds.length;
                    return callback(none), none;
                };

                //搜索匹配
                var search = function (e) {
                    var value = this.value, keyCode = e.keyCode;

                    if (keyCode === 9 || keyCode === 13
                        || keyCode === 37 || keyCode === 38
                        || keyCode === 39 || keyCode === 40
                    ) {
                        return false;
                    }

                    notOption(value, function (none) {
                        if (none) {
                            dl.find('.' + NONE)[0] || dl.append('<p class="' + NONE + '">无匹配项</p>');
                        } else {
                            dl.find('.' + NONE).remove();
                        }
                    }, 'keyup');

                    if (value === '') {
                        dl.find('.' + NONE).remove();
                    }

                    followScroll(); //定位滚动条
                };

                if (isSearch) {
                    input.on('keyup', search).on('blur', function (e) {
                        var selectedIndex = select[0].selectedIndex;

                        thatInput = input; //当前的 select 中的 input 元素
                        initValue = $(select[0].options[selectedIndex]).html(); //重新获得初始选中值

                        //如果是第一项，且文本值等于 placeholder，则清空初始值
                        if (selectedIndex === 0 && initValue === input.attr('placeholder')) {
                            initValue = '';
                        }

                        setTimeout(function () {
                            notOption(input.val(), function (none) {
                                initValue || input.val(''); //none && !initValue
                            }, 'blur');
                        }, 200);
                    });
                }

                //选择
                dds.on('click', function () {
                    var othis = $(this), value = othis.attr('lay-value'), values = [], titles = [], mselect = [];
                    var filter = select.attr('lay-filter'); //获取过滤器

                    if (othis.hasClass(DISABLED)) return false;


                    if (othis.hasClass('layui-select-tips')) {
                        input.val('');
                        select.attr('values','');
                    } else {
                        //切换选中状态
                        var checked = othis.find('.' + CHECK_CLASS).toggleClass(CHECKED_THIS).hasClass(CHECKED_THIS);
                        othis.find('input').prop('checked', checked);

                        dds.removeClass(SELECTED);
                        checks.removeClass(CHECKED_THIS);
                        select.children('option').prop('selected', false);
                        dds.find('input:checked').each(function () {
                            var dd = $(this).closest('dd'), value = dd.attr('lay-value'), check = $(this),
                                option = select.children('[value="' + value + '"]');
                            if (!check[0].disabled) {
                                check[0].checked = true;
                                check.addClass(CHECKED_THIS);
                                othis.addClass(SELECTED);
                                option.prop('selected', true);
                                mselect.push(dd);
                                values.push(value);
                                titles.push(dd.text());
                            }
                        });
                        othis.addClass(THIS).siblings().removeClass(THIS);

                        input.val(titles.join(','));
                        select.attr('values', values.join(',')).removeClass('layui-form-danger')
                    }
                    input.focus();
                    input.closest('.layui-form-select').addClass('layui-form-selected');

                    layui.event.call(this, MOD_NAME, 'mselect(' + filter + ')', {
                        elem: othis
                        , value: values
                        , text: titles
                        , othis: reElem
                    });

                    return false;
                });

                reElem.find('dl>dt').on('click', function (e) {
                    return false;
                });
                $(document).off('click', hide).on('click', hide); //点击其它元素关闭 select
            };
        mselect.each(function (index, select) {
            var othis = $(this)
                , hasRender = othis.next('.' + CLASS)
                , disabled = this.disabled
                , value = (select.values ? select.values.split(',') : [])
                , selected = $.map($(select.options), function (elem) {
                if ($.inArray(elem.value, value) != -1) {
                    return elem.innerHTML
                }
            });

            if (typeof othis.attr('lay-ignore') === 'string') return othis.show();

            var isSearch = typeof othis.attr('lay-search') === 'string'
                , placeholder = TIPS;

            //替代元素
            var reElem = $(['<div class="' + (isSearch ? '' : 'layui-unselect ') + CLASS
                , (disabled ? ' layui-select-disabled' : '') + '">'
                , '<div class="' + TITLE + '">'
                , ('<input type="text" placeholder="' + placeholder + '" '
                    + ('value="' + (value ? selected.join(',') : '') + '"') //默认值
                    + (isSearch ? '' : ' readonly') //是否开启搜索
                    + ' class="layui-input'
                    + (isSearch ? '' : ' layui-unselect')
                    + (disabled ? (' ' + DISABLED) : '') + '">') //禁用状态
                , '<i class="layui-edge"></i></div>'
                , '<dl class="layui-anim layui-anim-upbit' + (othis.find('optgroup')[0] ? ' layui-select-group' : '') + '">'
                , function (options) {
                    var arr = [];
                    layui.each(options, function (index, item) {
                        if (index === 0 && !item.value) {
                            arr.push('<dd lay-value="" class="layui-select-tips">' + (item.innerHTML || TIPS) + '</dd>');
                        } else if (item.tagName.toLowerCase() === 'optgroup') {
                            arr.push('<dt>' + item.label + '</dt>');
                        } else {
                            arr.push('<dd lay-value="' + item.value + '" class="' + (value === item.value ? SELECTED : '') + (item.disabled ? (' ' + DISABLED) : '') + '">');
                            arr.push('<input type="checkbox" lay-skin="primary" title="' + item.innerHTML +'" value="' + item.value + '" class="' + (value === item.value ? SELECTED : '') + (item.disabled ? (' ' + DISABLED) : '') + '">');
                            arr.push('<div class="layui-form-checkbox" lay-skin="primary"><span>' + item.innerHTML +'</span><i class="layui-icon layui-icon-ok"></i></div>');
                            arr.push('</dd>');
                        }
                    });
                    arr.length === 0 && arr.push('<dd lay-value="" class="' + DISABLED + '">没有选项</dd>');
                    return arr.join('');
                }(othis.find('*')) + '</dl>'
                , '</div>'].join(''));

            hasRender[0] && hasRender.remove(); //如果已经渲染，则rerender
            othis.after(reElem);
            events.call(this, reElem, disabled, isSearch);
        });
        return that;
    };

    //核心入口
    var multiSelect = new MultiSelect();
    multiSelect.render();

    //加载组件所需样式
    // layui.link(layui.cache.base + 'multiSelect/multiSelect.css?v=1', function () {
    //     //样式加载完毕的回调
    //
    // }, 'multiSelect'); //此处的“multiSelect”要对应 multiSelect.css 中的样式： html #layuicss-multiSelect{}

    exports('multiSelect', multiSelect);
});