$("div[data-prototype]").each(function(index) {
	var $self = $(this),
		count = $self.children().length,
		prototype = $self.data('prototype');

    $delete_trigger = $("<a />")
                            .attr({ href: '#' })
							.text('Delete element')
							.addClass('delete')
							.click(function(event) {
								$(this).parent().remove();
								return false;
							});

    $self.find("> div").append($delete_trigger.clone(true));

	$add_trigger = $("<a />")
                        .attr({ href: '#' })
						.addClass('add')
						.text('Add element')
						.appendTo(this)
						.click(function(event) {
						    $d = $delete_trigger.clone(true);
						    $p = $(prototype.replace(/\$\$name\$\$/g, count++)).append($d);
							$(this).before($p);
							return false;
						});
});