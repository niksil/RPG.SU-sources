/**
 * Searches through records and returns the index of the first match.
 * @param {String} field A field in the records being searched
 * @param {String/RegExp} value The string to compare to the field's value
 * @param {Number} startIndex Index to begin searching at (defaults to 0). (optional)
 * @param {Object} config (optional)
 */
Ext.data.Store.prototype.searchByString = function(field, value, startIndex) {
    var record = false, rc = this.getCount(), range;
    startIndex = startIndex || 0;
    startIndex = startIndex >= rc ? 0 : startIndex;

    if(!(value instanceof RegExp)) { // not a regex
        value = String(value);
        if(value.length === 0){
            return false;
        }
        value = new RegExp("^" + Ext.escapeRe(value), "i");
    }
    if(rc > 0) {
        range = this.getRange(startIndex);
        Ext.each(range, function(r) {
            if( value.test(r.data[field]) ) {
                record = r;
                return false;
            }
        });
        if( !record && startIndex > 0 ) {
            // if a startIndex was provided and we have no match, restart the search from the beginning
            return this.searchByString(field, value, 0);
        }
    }
    return record;
};

/*
This code is marriage of [b]GroupComboBox[/b] and [b]SelectBox[/b],
both third party contributions to this forum.  I started with
[b]SelectBox[/b], which is a very clean extension to
[b]Ext.form.ComboBox[/b] to make it behave like a normal HTTP select
box, and then extracted the excellent parts of [b]GroupComboBox[/b] that
provide the support for optgroups.

Usage:  input records should be as specified by the [b]GroupComboBox[/b]
example.  I've added some code to stylize the optgroup headers nicely,
as you can see in the picture below.
*/

/**
 * Original quote from Ext.form.SelectBox:
 * Makes a ComboBox more closely mimic an HTML SELECT.    Supports clicking and dragging
 * through the list, with item selection occurring when the mouse button is released.
 * When used will automatically set {@link #editable} to false and call {@link Ext.Element#unselectable}
 * on inner elements.  Re-enabling editable after calling this will NOT work.
 */
Ext.form.SelectGroupBox = function(config){
    this.searchResetDelay = 1000;
    config = config || {};
    config = Ext.apply(config || {}, {
        editable: false,
        forceSelection: true,
        rowHeight: false,
        lastSearchTerm: false
    });

    var cls = 'x-combo-list';
    this.tpl = '<div class="'+cls+'-item x-combo-list-hd">{' + config.groupField + '}</div><div class="'+cls+'-item x-combo-list-groupitem">{' + config.displayField + '}</div>';
    Ext.form.SelectGroupBox.superclass.constructor.apply(this, arguments);

    this.lastSelectedIndex = this.selectedIndex || 0;
};

Ext.extend(Ext.form.SelectGroupBox, Ext.form.ComboBox, {

    groupField: undefined,

    initEvents : function(){
        Ext.form.SelectGroupBox.superclass.initEvents.apply(this, arguments);
        // you need to use keypress to capture upper/lower case and shift+key, but it doesn't work in IE
        this.el.on('keydown', this.keySearch, this, true);
        this.on('beforeselect', this.beforeSelect, this, true);
        this.cshTask = new Ext.util.DelayedTask(this.clearSearchHistory, this);
    },

    expand: function()
    {
        var l = this.innerList.dom.childNodes.length - 1;

        for (var i=l; i>=0; i--)
        {
           var e = this.innerList.dom.childNodes[i];

            if(Ext.util.Format.trim(e.innerHTML).length === 0)
            {
                Ext.get(e).remove();
            }

        }

        this.view.updateIndexes();

        Ext.form.SelectGroupBox.superclass.expand.call(this);
    },

    keySearch : function(e, target, options) {
        var raw = e.getKey();
        var key = String.fromCharCode(raw);
        var startIndex = 0;

        if( !this.store.getCount() ) {
            return;
        }

        switch(raw) {
            case Ext.EventObject.HOME:
                e.stopEvent();
                this.selectFirst();
                return;

            case Ext.EventObject.END:
                e.stopEvent();
                this.selectLast();
                return;

            case Ext.EventObject.PAGEDOWN:
                this.selectNextPage();
                e.stopEvent();
                return;

            case Ext.EventObject.PAGEUP:
                this.selectPrevPage();
                e.stopEvent();
                return;
        }

        // skip special keys other than the shift key
        if( (e.hasModifier() && !e.shiftKey) || e.isNavKeyPress() || e.isSpecialKey() ) {
            return;
        }
        if( this.lastSearchTerm == key ) {
            startIndex = this.lastSelectedIndex;
        }
        this.search(this.displayField, key, startIndex);
        this.cshTask.delay(this.searchResetDelay);
    },

    onRender : function(ct, position) {
        this.store.on('load', this.calcRowsPerPage, this);
        Ext.form.SelectGroupBox.superclass.onRender.apply(this, arguments);
        if( this.mode == 'local' ) {
            this.calcRowsPerPage();
        }
    },

    onSelect : function(record, index, skipCollapse){
        if(this.fireEvent('beforeselect', this, record, index) !== false){
            this.setValue(record.data[this.valueField || this.displayField]);
            if( !skipCollapse ) {
                this.collapse();
            }
            this.lastSelectedIndex = index + 1;
            this.fireEvent('select', this, record, index);
        }
    },

    render : function(ct) {
        Ext.form.SelectGroupBox.superclass.render.apply(this, arguments);
        if( Ext.isSafari ) {
            this.el.swallowEvent('mousedown', true);
        }
        this.el.unselectable();
        this.innerList.unselectable();
        this.trigger.unselectable();
        this.innerList.on('mouseup', function(e, target, options) {
            if( target.id && target.id == this.innerList.id ) {
                return;
            }
            this.onViewClick();
        }, this);

        this.innerList.on('mouseover', function(e, target, options) {
            if( target.id && target.id == this.innerList.id ) {
                return;
            }
            this.lastSelectedIndex = this.view.getSelectedIndexes()[0] + 1;
            this.cshTask.delay(this.searchResetDelay);
        }, this);

        this.trigger.un('click', this.onTriggerClick, this);
        this.trigger.on('mousedown', function(e, target, options) {
            e.preventDefault();
            this.onTriggerClick();
        }, this);

        this.on('collapse', function(e, target, options) {
            Ext.get(document).un('mouseup', this.collapseIf, this);
        }, this, true);

        this.on('expand', function(e, target, options) {
            Ext.get(document).on('mouseup', this.collapseIf, this);
        }, this, true);
    },

    clearSearchHistory : function() {
        this.lastSelectedIndex = 0;
        this.lastSearchTerm = false;
    },

    selectFirst : function() {
        this.focusAndSelect(this.store.data.first());
    },

    selectLast : function() {
        this.focusAndSelect(this.store.data.last());
    },

    selectPrevPage : function() {
        if( !this.rowHeight ) {
            return;
        }
        var index = Math.max(this.selectedIndex-this.rowsPerPage, 0);
        this.focusAndSelect(this.store.getAt(index));
    },

    selectNextPage : function() {
        if( !this.rowHeight ) {
            return;
        }
        var index = Math.min(this.selectedIndex+this.rowsPerPage, this.store.getCount() - 1);
        this.focusAndSelect(this.store.getAt(index));
    },

    search : function(field, value, startIndex) {
        field = field || this.displayField;
        this.lastSearchTerm = value;
        var record = this.store.searchByString.apply(this.store, arguments);
        if( record !== false ) {
            this.focusAndSelect(record);
        }
    },

    focusAndSelect : function(record) {
        var index = this.store.indexOf(record);
        this.select(index, this.isExpanded());
        this.onSelect(record, index, this.isExpanded());
    },

    calcRowsPerPage : function() {
        if( this.store.getCount() ) {
            this.rowHeight = Ext.fly(this.view.getNode(0)).getHeight();
            this.rowsPerPage = this.maxHeight / this.rowHeight;
        } else {
            this.rowHeight = false;
        }
    },

    onViewClick : function(doFocus)
    {
        var index = this.view.getSelectedIndexes()[0];

        var r = this.store.getAt(index);

        if(r)
        {

            if(r.data.optgroup.length)
            {
                this.selectNext();
            }
            else
            {
                this.onSelect(r, index);
            }
        }
        if(doFocus !== false){
            this.el.focus();
        }
    },


    onViewOver : function(e, t)
    {
        if(this.inKeyMode){ // prevent key nav and mouse over conflicts
            return;
        }
        var item = this.view.findItemFromChild(t);

        if(item){
            var index = this.view.indexOf(item);

            if(Ext.get(item).hasClass('x-combo-list-hd'))
            {
                //this.selectNext();
            }
            else
            {
                this.select(index, false);
            }
        }
    },

    selectNext : function(){
        var ct = this.store.getCount();

        if(ct > 0)
        {
            var index = this.selectedIndex;

            if(index < ct-1)
            {
                var r = this.store.getAt(index+1);


                if(r.data.optgroup.length)
                {
                    this.selectedIndex += 1;

                    this.selectNext();
                }
                else
                {
                    this.select(index+1);
                }
            }
            else
            {
                this.selectedIndex = -1;

                this.selectNext();
            }
        }
    },

    selectPrev : function()
    {
        var ct = this.store.getCount();

        if(ct > 0)
        {
            var index = this.selectedIndex;
            var r;
            if(index === 0)
            {
                r = this.store.getAt(ct-1);

                if(r.data.optgroup.length)
                {
                    this.selectedIndex = ct;
                    this.selectPrev();
                }
                else
                {
                    this.select(ct-1);
                }
            }
            else
            {
                r = this.store.getAt(index-1);

                if(r.data.optgroup.length)
                {
                    this.selectedIndex -= 1;
                    this.selectPrev();
                }
                else
                {
                    this.select(index-1);
                }
            }
        }
    },

    beforeSelect : function(combo, record, index){
        if (record.data.text == "Add more...")
        {
            // Becuase the author implemented detection of mouseup, he introduced a sort of bug:
            // we get called here twice, once while expanded, and again after closed.  We only want
            // to do Add more... once, so we check for expanded, but we want to return false
            // both times, so that this item never stays selected.
            if (combo.isExpanded())
            {
                this.collapse();
                window.alert("Add more... will be implemented soon!");
            }
            return false; // i.e. cancel the selection of this item
        }
        return true;
    },

   onLoad : function(){
        if(!this.hasFocus){
            return;
        }
        if(this.store.getCount() > 0){
            this.expand();
            this.restrictHeight();
            if(this.lastQuery == this.allQuery){
                if(this.editable){
                    this.el.dom.select();
                }
                if(!this.selectByValue(this.value, true)){
                    this.selectNext(); // changed from this.select(0, true);
                }
            }else{
                this.selectNext();
                if(this.typeAhead && this.lastKey != Ext.EventObject.BACKSPACE && this.lastKey != Ext.EventObject.DELETE){
                    this.taTask.delay(this.typeAheadDelay);
                }
            }
        }else{
            this.onEmptyResults();
        }
    }
});