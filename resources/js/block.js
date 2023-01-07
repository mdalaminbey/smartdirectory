import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';

let Blocks = JSON.parse(Block.items);

for (let block in Blocks) {

	if (Object.hasOwnProperty.call(Blocks, block)) {
		
		let blockAttributes = Blocks[block];

		/**
		 * Block Unique Key
		 */
		let blockKey = `smart-directory/${block}`;

		registerBlockType(blockKey, {
			apiVersion: 2,
			title: blockAttributes.title,
			icon: blockAttributes.icon,
			category: 'widgets',
			edit: function (props) {
				return (
					<div {...useBlockProps()}>
						<ServerSideRender block={blockKey} attributes={props.attributes} />
					</div>
				);
			},
		});
	}
}