// Handle blocks output.
function handleBlocksOutput(element, blockType, attributes) {
  if (blockType.name === 'core/button' && element.props.hasOwnProperty('children')) {
    var childrenElem = element.props.children;
    if (childrenElem.props.className) {
      if ( childrenElem.props.className.indexOf('has') !== -1)
        element.props.children.props.className = childrenElem.props.className.replace('wp-block-button__link', 'btn');
      else
        element.props.children.props.className = `btn btn-primary`;
    }
  }
  if (blockType.name === 'core/video' && element.props.hasOwnProperty('className')) {
    element.props.className = element.props.className
      ? `${element.props.className} embed-responsive embed-responsive--16by9`
      : null;
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
  if (name === 'core/list') {
    return lodash.assign({}, settings, {
      attributes: lodash.assign({}, settings.attributes, {
        hasFlatList: {
          type: 'boolean',
          default: false
        }
      })
    });
  }
  if (name === 'core/button') {
    return lodash.assign({}, settings, {
      attributes: lodash.assign({}, settings.attributes, {
        borderRadius: {
          type: 'number',
          default: 0
        }
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

// Handle blocks styles.
var commonBlocks = ['core/paragraph', 'core/heading', 'core/list'];
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
wp.blocks.registerBlockStyle('core/columns', [
  {
    name: 'default',
    isDefault: true,
    label: wp.i18n.__('Orientamento regolare', '%DOMAIN_NAME%')
  },
  {
    name: 'reverse',
    label: wp.i18n.__('Orientamento inverso', '%DOMAIN_NAME%')
  }
]);

// Handle block variations.
wp.blocks.registerBlockVariation('core/heading', {
  icon: 'editor-bold',
  name: 'core/strong-heading',
  title: wp.i18n.__('Titolo forte', '%DOMAIN_NAME%'),
  attributes: {
    className: 'wp-strong-heading text-uppercase',
  }
});

// Handle heading block toolbar buttons.
var compose = wp.compose.compose;
var withSelect = wp.data.withSelect;
var ifCondition = wp.compose.ifCondition;
var buttonName = '%DOMAIN_NAME%/block-custom-heading-toolbar';
var buttonTitle = wp.i18n.__('Testo maiuscolo', '%DOMAIN_NAME%');
var TextUppercaseButton = function(props) {
  return wp.element.createElement(
    wp.blockEditor.RichTextToolbarButton, {
      icon: 'heading',
      title:  buttonTitle,
      onClick: function() {
        props.onChange(
          wp.richText.toggleFormat(props.value, {type: buttonName})
        );
      },
      isActive: props.isActive
    }
  );
}
var TextUppercaseButtonRender = compose(
  withSelect(function(select) {
    return {
      selectedBlock: wp.data.select('core/block-editor').getSelectedBlock()
    }
  }),
  ifCondition(function(props) {
    return (
      props.selectedBlock &&
      commonBlocks.indexOf(props.selectedBlock.name) !== -1
    )
  })
)(TextUppercaseButton);
wp.richText.registerFormatType(buttonName, {
  tagName: 'span',
  title: buttonTitle,
  className: 'text-uppercase',
  edit: TextUppercaseButtonRender
});

/**
 * Handle blocks blacklist & DOM ready.
 * @link https://wordpress.org/support/article/blocks/
 */
wp.domReady(function() {
  var disallowedBlocks = ['core/code', 'core/freeform', 'core/preformatted', 'core/audio', 'core/verse', 'core/more', 'core/nextpage'];
  var disallowedEmbeds = ['wordpress', 'soundcloud', 'animoto', 'cloudup', 'crowdsignal', 'dailymotion', 'hulu', 'imgur', 'issuu', 'kickstarter', 'meetup-com', 'mixcloud', 'reddit', 'reverbnation', 'screencast', 'scribd', 'smugmug', 'speaker-deck', 'ted', 'tumblr', 'videopress', 'wordpress-tv', 'amazon-kindle'];
  wp.blocks.getBlockTypes().forEach(function(blockType) {
    if (disallowedBlocks.indexOf(blockType.name) !== -1) {
      wp.blocks.unregisterBlockType(blockType.name);
    }
    if (blockType.name.indexOf('yoast/') !== -1 || blockType.name.indexOf('yoast-seo/') !== -1) {
      wp.blocks.unregisterBlockType(blockType.name);
    }
    if (blockType.name.indexOf('woocommerce/') !== -1) {
      wp.blocks.unregisterBlockType(blockType.name);
    }
    if (blockType.name.indexOf('yith/') !== -1) {
      wp.blocks.unregisterBlockType(blockType.name);
    }
  });
  for(var i = disallowedEmbeds.length - 1; i >= 0; i--) {
    wp.blocks.unregisterBlockVariation('core/embed', disallowedEmbeds[i]);
  }
});
