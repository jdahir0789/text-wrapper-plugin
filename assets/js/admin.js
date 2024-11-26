(function () {
	if (typeof tinymce === 'undefined') {
		console.error('TinyMCE not loaded.');
		return;
	}

	const defaul_value = ctwSettings.defaul_value;
	const options = ctwSettings.options || defaul_value;
	const tag = options.tag;
	const className = options.class;
	const highlightStyle = 'background-color: yellow; color: black;';

	tinymce.PluginManager.add('ctw_plugin', function (editor) {
		editor.addButton('ctw_button', {
			icon: 'insert',
			tooltip: 'Wrap Text (Ctrl+Alt+W)',
			onPostRender: function () {
				const btn = this;
				editor.on('NodeChange', function () {
					const selectedNode = editor.selection.getNode();
					const isWrapped = jQuery(selectedNode).is(`${tag}.${className}`) ||
						jQuery(selectedNode).closest(`${tag}.${className}`).length > 0;
					btn.active(isWrapped);
				});
			},
			onclick: function () {
				toggleWrapper(editor, tag, className);
			}
		});

		editor.addShortcut('meta+alt+w', 'Wrap text', function () {
			toggleWrapper(editor, tag, className);
		});
	});

	/**
	 * Toggles the wrapper around the selected text and highlight it
	 * @param {Object} editor - TinyMCE editor instance
	 * @param {string} tag - HTML tag to wrap
	 * @param {string} className - CSS class to add
	 */
	function toggleWrapper(editor, tag, className) {
		const selectedNode = editor.selection.getNode();
		const selectedText = editor.selection.getContent({ format: 'html' });

		const isWrapped = jQuery(selectedNode).is(`${tag}.${className}`) ||
			jQuery(selectedNode).closest(`${tag}.${className}`).length > 0;

		if (isWrapped) {
			const parentWrapper = jQuery(selectedNode).closest(`${tag}.${className}`);
			if (parentWrapper.length) {
				const unwrappedText = parentWrapper.html();
				parentWrapper.replaceWith(unwrappedText);
				editor.selection.getNode().style = '';
			}
		} else {
			if (!selectedText.trim()) {
				alert('Please select text to wrap.');
				return;
			}

			const wrappedText = `<${tag} class="${className}" style="${highlightStyle}">${selectedText}</${tag}>`;
			editor.insertContent(wrappedText);

			const newNode = editor.selection.getNode();
			if (newNode.nodeName.toLowerCase() === tag.toLowerCase()) {
				newNode.style.cssText = highlightStyle;
			}
		}
	}
})();
