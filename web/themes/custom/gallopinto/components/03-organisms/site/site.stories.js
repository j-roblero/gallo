import siteFooter from './site-footer/site-footer.twig';
import siteHeader from './site-header/site-header.twig';

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Site' };

export const footer = () => siteFooter({});
export const header = () => siteHeader({});
