import { addons } from '@storybook/manager-api';

import emulsifyTheme from './emulsifyTheme';

addons.setConfig({
  theme: emulsifyTheme,
});
