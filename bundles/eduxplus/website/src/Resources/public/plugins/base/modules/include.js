(function ($) {
  	"use strict";

	var promise = false,
		deferred = $.Deferred();
	_.templateSettings.interpolate = /{{([\s\S]+?)}}/g;
	$.fn.uiInclude = function(){
		if(!promise){
			promise = deferred.promise();
		}
		//console.log('start: includes');

		compile(this);

		function compile(node){
			node.find('[include]').each(function(){
				var that = $(this), url  = that.attr('include');
				if(!that) return ;
				promise = promise.then(
					function(){
						// console.log('start: compile '+ url);
                        url = url+"?"+parseInt(9999*Math.random());
						var request = $.ajax({
							url: url,
							method: "GET",
							dataType: "text"
						});
						//console.log('start: loading '+ url);
						var chained = request.then(
							function(text){
								// console.log(text.toString());
								var compiled = _.template(text.toString());
								var html = compiled();
								var ui = that.replaceWithPush( html );
								ui.find('[include]').length && compile(ui);
							}
						);
						return chained;
					}
				);
			});
		}

		deferred.resolve();
		return promise;
	}

	$.fn.replaceWithPush = function(o) {
	    var $o = $(o);
	    this.replaceWith($o);
	    return $o;
	}

})(jQuery);
