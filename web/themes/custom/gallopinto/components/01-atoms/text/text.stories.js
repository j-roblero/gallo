import heading from './headings/_heading.twig';
import paragraph from './text/01-paragraph.twig';

import paragraphData from './text/paragraph.yml';
import headingData from './headings/headings.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Atoms/Text' };

// Loop over items in headingData to show each one in the example below.
export const headings = () => headingData.map((d) => heading(d)).join('');
export const text = () => paragraph(paragraphData);
