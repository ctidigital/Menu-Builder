jQuery('document').ready(function() {
    // The menu
    var menuTree;

    jQuery(function () {
        // If the div exists
        if (jQuery('#menubuilder_tree').length > 0) {
            menuTree = jQuery('#menubuilder_tree').tree({
                dragAndDrop: true,
                autoOpen: 0
            });
        }
    });

    /**
     * Creates a new menu item. If an item is selected, it will be
     * added as a child node of that item.
     */
    jQuery('#add_menu_item').click(function () {
        // Get the selected menu item
        var selected = menuTree.tree('getSelectedNode');
        // Add a new node
        var newMenuItem = menuTree.tree(
            'appendNode',
            {
                label: 'New Item'
            },
            selected
        )
        var parentNodeId = (selected !== false) ? selected : 0;
        menuTree.tree(
            'updateNode',
            newMenuItem,
            {
                parent_id: parentNodeId.id
            }
        )
    });
});

function saveMenu()
{
    var editForm = jQuery('#edit_form');
    var jsonData = jQuery('#menubuilder_tree').tree('toJson');

    if (jQuery('#menu_tree').length > 0) {
        jQuery('#menu_tree').attr('value', jsonData);
    } else {
        jQuery('<input>').attr({
            type: 'hidden',
            id: 'menu_tree',
            name: 'menu_tree',
            value: jsonData
        }).appendTo(editForm);
    }

    editForm.submit();
}