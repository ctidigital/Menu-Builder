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

            menuTree.bind(
                'tree.select',
                function (event) {
                    if (event.node) {
                        showFieldForm(event.node);
                    } else {
                        hideFieldForm();
                    }
                }
            );
        }
    });

    /**
     * Creates a new menu item. If an item is selected, it will be
     * added as a child node of that item.
     */
    jQuery('#add_menu_item').click(function () {
        //Clear the form fields
        clearFields();
        // Get the selected menu item
        var selected = menuTree.tree('getSelectedNode');
        var fields = getFields();
        var itemData = {
            label: 'New Item'
        };
        jQuery.extend(itemData, fields);
        // Add a new node
        var newMenuItem = menuTree.tree(
            'appendNode',
            itemData,
            selected
        );
        var parentNodeId = (selected !== false) ? selected : 0;
        menuTree.tree(
            'updateNode',
            newMenuItem,
            {
                parent_id: parentNodeId.id
            }
        )
    });

    /**
     * Updates a menu item using the values from the form
     */
    jQuery('#save_menu_item').click(function () {
        var selected = menuTree.tree('getSelectedNode');
        var fields = getFields();
        menuTree.tree(
            'updateNode',
            selected,
            fields
        );
    });

    /**
     * Displays the field form and adds the node's values to it
     *
     * @param node
     */
    function showFieldForm (node)
    {
        // Get the fields
        var fields = jQuery('#cti_menubuilder_form_fields').find('input');
        // Add the node's value to the form field
        fields.each(function (item) {
            var name = jQuery(this).attr('name');
            if (node[name] !== undefined) {
                // Set the value of the input to the value of the node
                jQuery(this).val(node[name]);
            }
        });
        jQuery('#cti_menubuilder_fields').removeClass('visibly-hidden');
    }

    /**
     * Hides the field form
     */
    function hideFieldForm ()
    {
        jQuery('#cti_menubuilder_fields').addClass('visibly-hidden');
    }
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

/**
 * Gets the menu fields from the form so they can be added to the tree
 *
 * @returns {Array}
 */
function getFields ()
{
    var menuFields = jQuery('#cti_menubuilder_form_fields');
    var fieldData = [];

    if (menuFields.length > 0) {
        menuFields.find('input').each(function(element) {
            var name = jQuery(this).attr('name');
            var inputValue = jQuery(this).val();
            fieldData[name] = inputValue;
        });
    }

    return fieldData;
}

/**
 * Clears the menu fields
 */
function clearFields ()
{
    var menuFields = jQuery('#cti_menubuilder_form_fields');

    if (menuFields.length > 0) {
        menuFields.find('input').each(function(element) {
            jQuery(this).attr('value', '');
        });
    }
}