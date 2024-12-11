# Introduction to Hooks in PHP

Hooks are a flexible mechanism that allows you to inject additional logic into your existing application code. With them, you can dynamically modify the application's behavior by performing actions **before** or **after** the main methods in classes, without having to modify them.

This documentation aims to show you how to configure, use and extend hooks in PHP, giving you full control over your application processes and allowing you to customize them.

## Table of Contents:

- **[Hook Configuration](hook-config.md)**: Instructions for configuring hooks, including the use of `HookBefore` and `HookAfter` attributes to control the order of calls.
- **[Hook Priorities](hooks-priorities.md)**: A description of how to set the priorities of hooks, which determine the order in which they are executed.
- **[Hook Dependency (Under Deployment)](hook-dependency.md)**: Manage dependencies between hooks to ensure they are called correctly in the correct order. This feature is under implementation and may change in the future.
