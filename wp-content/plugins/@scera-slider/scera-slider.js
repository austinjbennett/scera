wp.blocks.registerBlockType('scera/scera-slider', {
	title: 'Scera Custom Slider',
	icon: 'smiley',
	category: 'common',
	attributes: {
		content: { type: 'string' },
		color: { type: 'string' },
	},
	edit(props) {
		function updateContent(event) {
			props.setAttributes({
				content: event.target.value,
			});
		}
		function updateColor(value) {
			props.setAttributes({
				color: value.hex,
			});
		}
		return wp.element.createElement('div', null, /* #__PURE__ */wp.element.createElement('h3', null, 'Your Cool Border Box'), /* #__PURE__ */wp.element.createElement('input', {
			type: 'text',
			value: props.attributes.content,
			onChange: updateContent,
		}), /* #__PURE__ */wp.element.createElement(wp.components.ColorPicker, {
			color: props.attributes.color,
			onChangeComplete: updateColor,
		}));
	},
	save(props) {
		return wp.element.createElement('h3', {
			style: {
				border: '5px solid '.concat(props.attributes.color),
			},
		}, props.attributes.content);
	},
});
