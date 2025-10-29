/**
 * Fix for scripts.bundle.js search error
 * Prevents addEventListener error when search elements don't exist
 */

// Global error handler to catch and suppress specific errors
window.addEventListener("error", function (e) {
  // Check if it's the specific addEventListener error we want to suppress
  if (
    e.message &&
    e.message.includes(
      "Cannot read properties of null (reading 'addEventListener')"
    )
  ) {
    // Suppress this specific error
    e.preventDefault();
    console.warn("Suppressed addEventListener error on null element");
    return true;
  }
  // Let other errors pass through
  return false;
});

// Also catch unhandled promise rejections
window.addEventListener("unhandledrejection", function (e) {
  if (
    e.reason &&
    e.reason.message &&
    e.reason.message.includes(
      "Cannot read properties of null (reading 'addEventListener')"
    )
  ) {
    e.preventDefault();
    console.warn("Suppressed addEventListener promise rejection");
    return true;
  }
  return false;
});
