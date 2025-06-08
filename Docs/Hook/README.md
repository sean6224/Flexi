# Introduction to Hooks in PHP

Hooks are a flexible mechanism that allows you to inject additional logic into your existing application code. With them, you can dynamically modify the application's behavior by performing actions **before** or **after** the main methods in classes, without having to modify them.

This documentation aims to show you how to configure, use and extend hooks in PHP, giving you full control over your application processes and allowing you to customize them.

## Table of Contents:
- **[Hook](Hook.md)**: Basic hook system supporting before & after hooks with priorities, attribute-based declarations, and automatic registration. Perfect for injecting modular logic without modifying core methods.
- **[Hook Dependency](Hook-dependency.md)**: Manage dependencies between hooks to ensure they are called correctly in the correct order. This feature is under implementation and may change in the future.
