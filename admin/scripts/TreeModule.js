var UITreeview = function () {
    "use strict";
    function customMenu(node) {
        if (!node.data.disabled) {
            var items = {
                createItem: { // The "rename" menu item
                    label: "Sukurti",
                    action: function () {
                        $.ajax({
                            url: "/ajax/get_jstree.php",
                            data: {
                                action: 'new_menu',
                                parent: node.parent.split('_')[1],
                                lang: $("div.active").attr("id").split('_')[1]
                            },
                            type : 'post'
                        }).done(function() {
                            location.reload();
                        });

                    }
                },
                editItem: { // The "rename" menu item
                    label: "Redaguoti",
                    action: function () {
                        //POST node.data.id
                    }
                },
                visibleItem: { // The "rename" menu item
                    label: "Publikuoti",
                    action: function () {
                        $.ajax({
                            type: "GET",
                            url: "/ajax/get_jstree.php",
                            data: {
                                id: node.data.id,
                                action: 'enable'
                            },
                            success: function (data) {
                                location.reload();
                            }
                        });
                    },

                    _disabled: node.type == 'disabled' ? false : true
                },
                invisibleItem: { // The "rename" menu item
                    label: "Nepublikuoti",
                    action: function () {
                        $.ajax({
                            type: "GET",
                            url: "/ajax/get_jstree.php",
                            data: {
                                id: node.data.id,
                                action: 'disable'
                            },
                            success: function (data) {
                                location.reload();
                            }
                        });
                    },
                    _disabled: node.type == 'disabled' ? true : false
                },
                deleteItem: { // The "delete" menu item
                    label: "Šalinti",
                    action: function () {
                        var r = null;
                        if (node.children.length > 0) {
                            r = confirm("Ar tikrai norite ištrinti? Bus ištrinti ir jame esantys vaikai");
                        } else {
                            r = confirm("Ar tikrai norite ištrinti?");
                        }
                        if (r == true) {
                            $.ajax({
                                type: "GET",
                                url: "/ajax/get_jstree.php",
                                data: {
                                    id: node.data.id,
                                    action: 'delete'
                                },
                                success: function (data) {
                                    alert("Ištrinta");
                                    location.reload();
                                }
                            });
                        }
                    }
                }
            }
        }
        if ($(node).hasClass("folder")) {
            // Delete the "delete" menu item
            delete items.deleteItem;
        }

        return items;
    }

    function setup(tree) {
        tree.jstree({
            'core': {
                "themes": {
                    "responsive": false
                },
                // so that create works
                "check_callback": true,
                "load_open": true,
                'data': {
                    "url": '/ajax/get_jstree.php?action=menu&lang='+$("div.active").attr("id").split('_')[1]
                }
            },
            "types": {
                "default": {
                    "icon": "fa fa-file text-green fa-lg"
                },
                "root": {
                    "icon": "fa fa-briefcase text-blue fa-lg"
                },
                "parent": {
                    "icon": "fa fa-folder text-yellow fa-lg"
                },
                "file": {
                    "icon": "fa fa-file text-green fa-lg"
                },
                "disabled": {
                    "icon": "fa fa-file text-gray fa-lg"
                }
            },
            "state": {
                "key": "demo2"
            },
            "plugins": ["dnd", "types", "contextmenu", "state"],
            "contextmenu": {
                items: customMenu,
                select_node: false
            }
        });

        tree.bind("move_node.jstree", function (e, data) {
            var last = data.node.parents[data.node.parents.length - 2].split('_')[1];
            var elements = [];
            $("#"+data.parent +" > ul > li").each(function(index, elm) {
                elements[index] = {'node_id' : $(elm).attr('id'), 'order' : index};

            });
            console.log(data.node);
                $.ajax({
                    url: "/ajax/get_jstree.php",
                    data: {
                        parent: data.node.parent,
                        order: data.position,
                        id: data.node.data.id,
                        position: last,
                        action: 'dnd',
                        children_all: data.node.children_d,
                        elements: elements
                    },
                    type : 'post'

                });

        });
        tree.bind("activate_node.jstree", function (e, data) {
            data.instance.save_state();
            location.href = updateQueryStringParameter(location.search,'id',data.node.id);
            console.log(data);
        });
    }
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            return uri + separator + key + "=" + value;
        }
    }
    return {
        setupTree: function (object) {
            setup(object);
        }
    }
}();
