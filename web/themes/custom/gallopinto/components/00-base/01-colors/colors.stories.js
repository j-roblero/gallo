import colors from './colors.twig';
import colorsData from './colors.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Base/Colors' };

export function Palettes() {
  return colors(colorsData);
}
