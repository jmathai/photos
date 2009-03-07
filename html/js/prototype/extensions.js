/* when finished, don't jump back to the top of the page. 
 */
Object.extend(SortableObserver, {
  initialize: function(element, observer) {
    alert('...'+this.element);
    this.element   = $(element);
    this.element   = $(element);
    this.observer  = observer;
    this.lastValue = Sortable.serialize(this.element);
  }})

