import { test, expect } from '@playwright/test';

test('order management page is reachable', async ({ page }) => {
  await page.goto('/manage/orders');
  await expect(page.getByText('Create demo order')).toBeVisible();
});
