$(document).observe("dom:loaded",function(dom_loaded_event) {
	$$("[name=menu_hover]").each(function(el,idx) {
		el.observe("mouseover",function(mouseover_event) {
			var event_element = mouseover_event.element();
			var target = getMenuContent(event_element);
			var menu = getMenu(event_element);
			var anchor = menu.down("a");
			if(typeof(target.timeout) == "undefined") {
				target.timeout = null;
			}	
			if(target.timeout != null) {
			clearTimeout(target.timeout);
			}
			if(!target.visible() && (event_element == menu || event_element == anchor)) {
				Effect.toggle(
					target,
					'Appear',
					{
						duration: .15
					}	
				);
			}
		});

		el.observe("mouseout",function(mouseout_event) {
			var event_element = mouseout_event.element();
			var target = getMenuContent(event_element);
			var menu = getMenu(event_element);
			var id = target.identify();
			target.timeout = setTimeout("toggleById(\"" + id + "\");", 100);
		});
	});
});

// used by setTimeout as we need a small delay before hiding a element to account for
// the fraction of a second between the mouseout and mouseover events firing
function toggleById(id) {
	var target = $(id);
	if(target.visible()) {
		Effect.toggle(
			target,
			'Appear',
			{
				duration: .2
			}
		);
	}
}

function getMenu(el) {
	var base = el;
	if(base.readAttribute("name") != "menu_hover") {
		base = base.up("[name=menu_hover]");
	}
	return base;
}

function getMenuContent(el) {
	var base = getMenu(el);
	return base.down("[name=menu_content]");
}
