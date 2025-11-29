/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Advanced Console Logger - Comprehensive event tracking system
 * Developed by Amin Davodian
 */

class ConsoleLogger {
    constructor() {
        this.startTime = Date.now();
        this.eventCounter = 0;
        this.init();
    }

    init() {
        this.displayWelcome();
        this.setupAjaxLogging();
        this.setupErrorLogging();
        
        // Wait for DOM to be ready before initializing UI elements
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.initProjectorMode();
                this.initVisualLogger();
                this.setupFormLogging();
                this.logPageLoad();
            });
        } else {
            this.initProjectorMode();
            this.initVisualLogger();
            this.setupFormLogging();
            this.logPageLoad();
        }
    }

    // Initialize Projector Mode (High Contrast)
    initProjectorMode() {
        // Create Console Toggle Button
        const consoleBtn = document.createElement('div');
        consoleBtn.id = 'console-toggle';
        consoleBtn.innerHTML = '📟';
        consoleBtn.title = 'Toggle Console Logger';
        consoleBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 10002;
            background: var(--card-bg);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 0 20px var(--primary-glow);
            transition: all 0.3s;
        `;
        document.body.appendChild(consoleBtn);

        // Toggle Console Logger
        consoleBtn.addEventListener('click', () => {
            const logger = document.getElementById('visual-logger');
            if (logger) {
                logger.classList.toggle('visible');
            }
        });

        // Create Projector Toggle Button
        const btn = document.createElement('div');
        btn.id = 'projector-toggle';
        btn.innerHTML = '📽️';
        btn.title = 'Toggle Projector Mode';
        document.body.appendChild(btn);

        // Check LocalStorage
        const isProjectorMode = localStorage.getItem('projectorMode') === 'true';
        if (isProjectorMode) {
            document.documentElement.classList.add('projector-mode');
        }

        // Toggle Event
        btn.addEventListener('click', () => {
            document.documentElement.classList.toggle('projector-mode');
            const isActive = document.documentElement.classList.contains('projector-mode');
            localStorage.setItem('projectorMode', isActive);

            this.log('System', `Projector Mode: ${isActive ? 'ON' : 'OFF'}`, 'info');
        });
    }

    // Initialize Visual Logger (On-Screen Terminal)
    initVisualLogger() {
        const container = document.createElement('div');
        container.id = 'visual-logger';
        container.innerHTML = `
            <div class="vl-header" onclick="document.getElementById('visual-logger').classList.toggle('minimized')">
                <div class="vl-title">
                    <span>📟</span> Live System Logs
                </div>
                <div class="vl-controls">
                    <button class="vl-btn" onclick="event.stopPropagation(); logger.clearVisualLog(); sessionStorage.removeItem('consoleLogs');">Clear</button>
                    <button class="vl-btn" onclick="event.stopPropagation(); document.getElementById('visual-logger').classList.toggle('minimized')">_</button>
                </div>
            </div>
            <div class="vl-content" id="vl-content"></div>
        `;
        document.body.appendChild(container);
        this.vlContent = document.getElementById('vl-content');

        // Restore logs from previous session
        this.restoreLogs();

        // Add click handler to header to show/hide logger
        const header = container.querySelector('.vl-header');
        if (header) {
            header.addEventListener('click', (e) => {
                if (e.target.closest('.vl-btn')) return; // Don't toggle if clicking buttons
                container.classList.toggle('visible');
            });
        }

        // Don't auto-show - user must click to open
    }

    // Append to Visual Logger
    appendToVisualLog(type, message, details = null) {
        if (!this.vlContent) return;

        const entry = document.createElement('div');
        entry.className = `vl-entry log-${type}`;

        const time = new Date().toLocaleTimeString().split(' ')[0];
        let html = `<span class="vl-timestamp">[${time}]</span>`;

        // Icons based on type
        const icons = {
            info: 'ℹ️',
            success: '✅',
            warning: '⚠️',
            error: '❌',
            security: '🔒'
        };

        html += `<strong>${icons[type] || ''} ${message}</strong>`;

        if (details) {
            if (typeof details === 'object') {
                html += `<pre style="margin: 5px 0 0 0; font-size: 0.9em; background: rgba(0,0,0,0.1); padding: 5px;">${JSON.stringify(details, null, 2)}</pre>`;
            } else {
                html += `<div style="margin-top: 5px; opacity: 0.8;">${details}</div>`;
            }
        }

        entry.innerHTML = html;
        this.vlContent.appendChild(entry);
        this.vlContent.scrollTop = this.vlContent.scrollHeight;

        // Save to sessionStorage for persistence across page reloads
        this.saveLogs();
    }

    // Save logs to sessionStorage
    saveLogs() {
        if (!this.vlContent) return;
        const logsHTML = this.vlContent.innerHTML;
        sessionStorage.setItem('consoleLogs', logsHTML);
    }

    // Restore logs from sessionStorage
    restoreLogs() {
        if (!this.vlContent) return;
        const savedLogs = sessionStorage.getItem('consoleLogs');
        if (savedLogs) {
            this.vlContent.innerHTML = savedLogs;
            this.vlContent.scrollTop = this.vlContent.scrollHeight;
        }
    }

    clearVisualLog() {
        if (this.vlContent) this.vlContent.innerHTML = '';
    }

    // Display welcome message - Developed by Amin Davodian
    displayWelcome() {
        console.clear();
        console.log('%c🔐 CYBER SECURITY LAB - CONSOLE LOGGER',
            'color: #10b981; font-size: 20px; font-weight: bold; text-shadow: 0 0 10px #10b981;');
        console.log('%cDeveloped by Amin Davodian',
            'color: #8b5cf6; font-style: italic;');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━',
            'color: #334155;');
        console.log('%cℹ️ Console logging is now active. All events will be tracked here.',
            'color: #94a3b8;');
        console.log(' ');

        // Visual Log Welcome
        setTimeout(() => {
            this.appendToVisualLog('info', 'System Initialized', 'Logger active. Waiting for events...');
        }, 500);
    }

    // Get formatted timestamp
    getTimestamp() {
        const elapsed = ((Date.now() - this.startTime) / 1000).toFixed(2);
        return `[+${elapsed}s]`;
    }

    // Log page load event
    logPageLoad() {
        this.eventCounter++;
        console.group(`%c📄 #${this.eventCounter} PAGE LOADED ${this.getTimestamp()}`,
            'color: #10b981; font-weight: bold;');
        console.log('%cURL:', 'color: #8b5cf6; font-weight: bold;', window.location.href);
        console.log('%cTitle:', 'color: #8b5cf6; font-weight: bold;', document.title);
        console.groupEnd();

        this.appendToVisualLog('info', 'Page Loaded', window.location.pathname.split('/').pop());
    }

    // Setup form submission logging
    setupFormLogging() {
        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('form');
            forms.forEach((form, index) => {
                form.addEventListener('submit', (e) => {
                    this.logFormSubmission(form, e);
                });

                // Log input changes
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('change', (e) => {
                        this.logInputChange(input, e);
                    });
                });
            });
        });
    }

    // Log form submission
    logFormSubmission(form, event) {
        this.eventCounter++;
        const formData = new FormData(form);
        const method = form.method.toUpperCase() || 'GET';
        const action = form.action || window.location.href;

        const dataObject = {};
        for (let [key, value] of formData.entries()) {
            dataObject[key] = key.toLowerCase().includes('pass') ? '••••••••' : value;
        }

        console.group(`%c📡 #${this.eventCounter} FORM SUBMIT ${this.getTimestamp()}`,
            'color: #f59e0b; font-weight: bold; font-size: 14px;');
        console.log('%cMethod:', 'color: #8b5cf6;', method);
        console.table(dataObject);
        console.groupEnd();

        this.appendToVisualLog('warning', `Form Submission (${method})`, dataObject);
    }

    // Log input changes
    logInputChange(input, event) {
        const value = input.value;
        const name = input.name || input.id || 'unnamed';
        const isPassword = input.type === 'password';

        console.log(`%c🔄 Input Change: %c${name}`, 'color: #94a3b8;', 'color: #10b981; font-weight: bold;');

        // Only log to visual logger if it's significant
        // this.appendToVisualLog('info', `Input Changed: ${name}`, isPassword ? '(hidden)' : value);
    }

    // Setup AJAX logging
    setupAjaxLogging() {
        const originalFetch = window.fetch;
        const self = this;

        window.fetch = function (...args) {
            self.eventCounter++;
            const url = args[0];
            const options = args[1] || {};

            console.group(`%c🌐 #${self.eventCounter} AJAX REQUEST ${self.getTimestamp()}`,
                'color: #3b82f6; font-weight: bold;');
            console.log('URL:', url);
            console.groupEnd();

            self.appendToVisualLog('info', 'AJAX Request', `URL: ${url}`);

            return originalFetch.apply(this, args)
                .then(response => {
                    self.appendToVisualLog('success', 'AJAX Response', `Status: ${response.status}`);
                    return response;
                })
                .catch(error => {
                    self.appendToVisualLog('error', 'AJAX Error', error.message);
                    throw error;
                });
        };
    }

    // Setup error logging
    setupErrorLogging() {
        window.addEventListener('error', (event) => {
            this.eventCounter++;
            console.group(`%c❌ #${this.eventCounter} ERROR ${this.getTimestamp()}`,
                'color: #ef4444; font-weight: bold;');
            console.log(event.message);
            console.groupEnd();

            this.appendToVisualLog('error', 'JavaScript Error', `${event.message}\nLine: ${event.lineno}`);
        });
    }

    // Custom logging methods for specific events
    logSecurity(type, details) {
        this.eventCounter++;
        console.group(`%c🔒 #${this.eventCounter} SECURITY EVENT: ${type}`, 'color: #ef4444; font-weight: bold;');
        console.log(details);
        console.groupEnd();

        this.appendToVisualLog('security', `Security Alert: ${type}`, details);
    }

    logValidation(fieldName, isValid, message) {
        const type = isValid ? 'success' : 'error';
        this.appendToVisualLog(type, `Validation: ${fieldName}`, message);
    }

    logXSS(payload, isSanitized) {
        this.eventCounter++;
        const type = isSanitized ? 'success' : 'security';
        const msg = isSanitized ? 'XSS Attempt Blocked' : 'XSS Vulnerability Triggered!';

        console.group(`%c💉 #${this.eventCounter} XSS ATTEMPT`, 'color: #ef4444;');
        console.log('Payload:', payload);
        console.groupEnd();

        this.appendToVisualLog(type, msg, `Payload: ${payload}`);
    }

    logSQL(query, isSafe) {
        this.eventCounter++;
        const type = isSafe ? 'success' : 'security';
        const msg = isSafe ? 'SQL Query (Safe)' : 'SQL Injection Detected!';

        this.appendToVisualLog(type, msg, query);
    }

    logCSRF(tokenGenerated, tokenValid) {
        if (tokenGenerated) {
            this.appendToVisualLog('info', 'CSRF Token Generated', tokenGenerated);
        }
        if (tokenValid !== undefined) {
            const type = tokenValid ? 'success' : 'security';
            this.appendToVisualLog(type, 'CSRF Check', tokenValid ? 'Token Valid' : 'Invalid Token!');
        }
    }

    logFileUpload(filename, size, type, isAllowed) {
        const status = isAllowed ? 'success' : 'security';
        const msg = isAllowed ? 'File Uploaded' : 'Upload Blocked';
        this.appendToVisualLog(status, msg, `File: ${filename} (${(size / 1024).toFixed(2)}KB)`);
    }

    logChat(message, username, isSanitized) {
        const type = isSanitized ? 'success' : 'security';
        this.appendToVisualLog(type, `Chat: ${username}`, message);
    }

    // Custom log method
    log(message, details = '', type = 'info') {
        const colors = { info: '#3b82f6', success: '#10b981', warning: '#f59e0b', error: '#ef4444' };
        console.log(`%c${message}`, `color: ${colors[type]}; font-weight: bold;`, details);

        this.appendToVisualLog(type, message, details);
    }
}

// Initialize the logger - Developed by Amin Davodian
window.logger = new ConsoleLogger();

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ConsoleLogger;
}

console.log('%c✨ Console Logger initialized successfully!',
    'color: #10b981; font-weight: bold;');
