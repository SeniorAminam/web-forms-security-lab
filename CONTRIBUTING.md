# Contributing Guide

Thank you for your interest in improving **Web Security Lab - Hacker Edition**.
This project is designed as an educational lab for web security and web forms.

## Development Setup

- PHP 7.4+ (recommended: 8.1+)
- Start local dev server:

```bash
php -S localhost:8000
# then open http://localhost:8000/dashboard.php
```

## Code Style

- **PHP:** follow PSR-1, PSR-4, PSR-12
- Use English identifiers for variables, functions, and classes
- Keep code modular, clean, and well-structured
- For new PHP files, start with a header comment including:
  - Project name
  - Author: Amin Davodian (Mohammadamin Davodian)
  - Website: https://senioramin.com
  - GitHub: https://github.com/SeniorAminam
  - Date of creation
  - Note: "Developed by Amin Davodian"

## Security First

This lab intentionally contains vulnerable examples **for learning purposes**.
When adding or modifying code:

- Clearly separate **vulnerable** and **secure** examples
- Add short inline comments explaining why something is vulnerable / secure
- Always escape user output with `htmlspecialchars()` in secure examples
- Never store real credentials or secrets in the repository

## Reset All Feature (NEW!)

The project now includes a **Reset All** button (`reset_all.php`) that:
- Clears all session data
- Resets chat messages
- Restores database to initial state
- Removes uploaded files

When adding new features that store data:
- Update `reset_all.php` to include cleanup for your data
- Test the reset functionality thoroughly

## Git Workflow

- Create a feature branch from `main`
- Write clear, descriptive commit messages
- Keep pull requests focused and small when possible

## Tests & CI

- GitHub Actions run basic PHP linting for all `.php` files
- Please make sure your code passes `php -l` checks before opening a PR
- If you add more advanced tests later (e.g. PHPUnit), document them in the README

## Reporting Security Issues

This is an educational project. If you find an issue in the demos or docs:

- Open an issue with a clear description and reproduction steps
- Or submit a pull request with a suggested fix/improvement

---

Developed by **Amin Davodian (Mohammadamin Davodian)**  
Website: https://senioramin.com  
GitHub: https://github.com/SeniorAminam
