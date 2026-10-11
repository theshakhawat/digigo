---
paths:
  - 'resources/views/**'
---

# Views

## Always Use Custom Delete Confirmation Modal Instead of Browser Confirm
Never use browser default confirm() or onsubmit="return confirm(...)". Always use custom delete confirmation dialogs (e.g. .js-custom-delete trigger and #customDeleteModal) with custom backdrop, item-specific message, and styled Yes/Cancel actions.
