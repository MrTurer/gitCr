let HeatMapExt = {
    apiUrl: null,
    userId: null,
    init: function () {
        let me = this;
        BX.bindDelegate(
            document.body,
            'click',
            {},
            function (e) {
                var element = e.target;
                var path = me.getDomPath(element);
                var url = element.baseURI;

                if (element.className === 'heatmap-canvas') {
                    return
                }

                BX.ajax.post(
                    me.apiUrl + '/addClick',
                    {
                        'url': url,
                        'path': path,
                        'user': me.userId
                    }
                );
            }
        );

        BX.append(BX.create('button', {
            attrs: {
                className: 'ui-btn ui-btn-success'
            },
            events: {
                click: function () {
                    me.createHeatMap();
                }
            },
            text: 'Карта'
        }), BX('copyright'));
    },
    createHeatMap: function () {
        BX.prepend(BX.create('div', {
            attrs: {
                id: 'heatmapContainerWrapper'
            },
            style: {
                width: "100%",
                height: "100%",
                position: "absolute",
                "z-index": 991
            },
            events: {
                click: function (e) {
                    BX.remove(BX('heatmapContainerWrapper'));
                }
            },
            children: [
                BX.create('div', {
                    attrs: {
                        id: 'heatmapContainer'
                    },
                    style: {
                        width: "100%",
                        height: "100%"
                    }
                })
            ]
        }), document.body);

        var heatmap = h337.create({
            container: BX('heatmapContainer'),
            maxOpacity: .7,
            minOpacity: .05,
            radius: 100,
            blur: .85,
            backgroundColor: 'rgba(50, 120, 255, 0.25)'
        });

        BX.ajax.runAction('rns:analytics.api.Heatmap.getClicks', {
            data: {
                'url': window.location.href,
                'users': 1,
                'fromDate': '08.02.2021 22:00',
                'toDate': '08.02.2021 23:00'
            }
        }).then(function (response) {
            result = BX.parseJSON(response.data);
            if (result.success) {
                result.data.forEach(function (click) {
                    var element = document.querySelector(click.path);
                    if (element) {
                        var rect = element.getBoundingClientRect();
                        var x = (rect.left + rect.right) / 2;
                        var y = (rect.top + rect.bottom) / 2;
                        heatmap.addData({x: x, y: y, value: click.count});
                    }
                });
            }

        }, function (response) {
            alert('Ошибка');
        });
    },
    getDomPath: function (el) {
        var stack = [];
        while (el.parentNode != null) {
            var sibCount = 0;
            var sibIndex = 0;
            for (var i = 0; i < el.parentNode.childNodes.length; i++) {
                var sib = el.parentNode.childNodes[i];
                if (sib.nodeName === el.nodeName) {
                    if (sib === el) {
                        sibIndex = sibCount;
                    }
                    sibCount++;
                }
            }
            if (el.hasAttribute('id') && el.id !== '' && (el.id.search(/[0-9]/) === -1)) {
                stack.unshift(el.nodeName.toLowerCase() + '#' + el.id);
            } else if (sibCount > 1) {
                stack.unshift(el.nodeName.toLowerCase() + ':nth-of-type(' + (sibIndex + 1) + ')');
            } else {
                stack.unshift(el.nodeName.toLowerCase());
            }
            el = el.parentNode;
        }
        stack = stack.slice(1) // removes the html element
        return stack.join(' > ');
    }
}
