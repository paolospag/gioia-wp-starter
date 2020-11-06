// Handle blocks output
function handleBlocksOutput(element, blockType, attributes) {
  if (blockType.name === 'core/button' && element.props.hasOwnProperty('children')) {
    var childrenElem = element.props.children;
    element.props.children.props.className = `btn btn-primary ${childrenElem.props.className}`;
  }
  if (blockType.name === 'core/paragraph') {
    // element.props.tagName = 'div';
    element.props.className = element.props.className
      ? `wp-block-text ${element.props.className}`
      : 'wp-block-text';
    // element.props.value = `<p>${element.props.value}</p>`;
  }
  if (blockType.name === 'core/heading') {
    element.props.className = element.props.className
      ? `wp-block-heading ${element.props.className}`
      : 'wp-block-heading';
  }
  return element;
}
wp.hooks.addFilter(
  'blocks.getSaveElement',
  '%DOMAIN_NAME%/block-custom-output',
  handleBlocksOutput,
  5
);

// Handle blocks setting.
var defaultWideBlocks = ['core/columns', 'core/group', 'core/cover'];
function handleBlocksSetting(settings, name) {
  if (defaultWideBlocks.indexOf(name) !== -1) {
    return lodash.assign({}, settings, {
      attributes: lodash.assign({}, settings.attributes, {
        align: {
          type: 'string',
          default: 'wide',
        }
      }),
      supports: lodash.assign({}, settings.supports, {
        anchor: true,
        align: ['wide', 'full'],
        default: 'wide'
      })
    });
  }
  return settings;
}
wp.hooks.addFilter(
  'blocks.registerBlockType',
  '%DOMAIN_NAME%/block-custom-setting',
  handleBlocksSetting,
  5
);

// Handle blocks style.
var commonBlocks = ['core/paragraph', 'core/heading'];
commonBlocks.forEach(function(name) {
  wp.blocks.registerBlockStyle(name, [
    {
      name: 'default',
      isDefault: true,
      label: wp.i18n.__('Larghezza regolare', '%DOMAIN_NAME%')
    },
    {
      name: 'small',
      label: wp.i18n.__('Larghezza contenuta', '%DOMAIN_NAME%')
    }
  ]);
});
var layoutBlocks = ['core/column', 'core/group'];
layoutBlocks.forEach(function(name) {
  wp.blocks.registerBlockStyle(name, [
    {
      name: 'default',
      isDefault: true,
      label: 'Default'
    },
    {
      name: 'primary',
      label: wp.i18n.__('Primario', '%DOMAIN_NAME%')
    },
    {
      name: 'secondary',
      label: wp.i18n.__('Secondario', '%DOMAIN_NAME%')
    }
  ]);
});

/**
 * Handle blocks blacklist on DOM ready.
 * @link https://wordpress.org/support/article/blocks/
 */
wp.domReady(function() {
  var disallowedBlocks = ['core/audio', 'core/verse', 'core/more', 'core/nextpage', 'core/social-links', 'core-embed/wordpress', 'core-embed/soundcloud', 'core-embed/flickr', 'core-embed/animoto', 'core-embed/cloudup', 'core-embed/crowdsignal', 'core-embed/dailymotion', 'core-embed/hulu', 'core-embed/imgur', 'core-embed/issuu', 'core-embed/kickstarter', 'core-embed/reddit', 'core-embed/reverbnation', 'core-embed/screencast', 'core-embed/scribd', 'core-embed/smugmug', 'core-embed/speaker-deck', 'core-embed/ted', 'core-embed/videopress', 'core-embed/wordpress-tv', 'core-embed/amazon-kindle'];
  wp.blocks.getBlockTypes().forEach(function(blockType) {
    if (disallowedBlocks.indexOf(blockType.name) !== -1) {
      wp.blocks.unregisterBlockType(blockType.name);
    }
  });
});
