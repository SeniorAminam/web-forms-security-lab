/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Interactive Terminal Emulator
 * Developed by Amin Davodian
 */

class CyberTerminal {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.history = [];
        this.historyIndex = -1;
        this.commands = {
            help: this.showHelp.bind(this),
            clear: this.clear.bind(this),
            xss: this.demonstrateXSS.bind(this),
            sql: this.demonstrateSQL.bind(this),
            csrf: this.demonstrateCSRF.bind(this),
            scanner: this.runScanner.bind(this),
            about: this.showAbout.bind(this),
            payloads: this.showPayloads.bind(this)
        };

        this.init();
    }

    init() {
        if (!this.container) return;

        this.container.innerHTML = `
            <div class="terminal-window">
                <div class="terminal-header">
                    <span class="terminal-title">🔒 CYBER SECURITY TERMINAL v2.0</span>
                    <span class="terminal-controls">
                        <span class="term-btn minimize">─</span>
                        <span class="term-btn maximize">□</span>
                        <span class="term-btn close">✕</span>
                    </span>
                </div>
                <div class="terminal-body" id="terminalOutput">
                    <div class="terminal-line">
                        <span class="prompt">root@security:~#</span>
                        <span class="command">Welcome to Cyber Security Terminal</span>
                    </div>
                    <div class="terminal-line">
                        <span class="info">Type 'help' for available commands</span>
                    </div>
                </div>
                <div class="terminal-input-line">
                    <span class="prompt">root@security:~#</span>
                    <input type="text" id="terminalInput" class="terminal-input" autofocus>
                </div>
            </div>
        `;

        this.output = document.getElementById('terminalOutput');
        this.input = document.getElementById('terminalInput');

        this.input.addEventListener('keydown', this.handleKeyPress.bind(this));
        this.container.addEventListener('click', () => this.input.focus());

        this.addStyles();
    }

    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .terminal-window {
                background: #000;
                border: 1px solid var(--primary-color);
                border-radius: 4px;
                overflow: hidden;
                box-shadow: 0 0 30px rgba(16, 185, 129, 0.3);
                font-family: 'Courier New', monospace;
            }
            
            .terminal-header {
                background: #1a1a1a;
                padding: 0.5rem 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid var(--primary-color);
            }
            
            .terminal-title {
                color: var(--primary-color);
                font-weight: bold;
                font-size: 0.9rem;
            }
            
            .terminal-controls {
                display: flex;
                gap: 0.5rem;
            }
            
            .term-btn {
                color: #666;
                cursor: pointer;
                user-select: none;
            }
            
            .term-btn.close:hover { color: #ef4444; }
            .term-btn.maximize:hover { color: #f59e0b; }
            .term-btn.minimize:hover { color: var(--primary-color); }
            
            .terminal-body {
                padding: 1rem;
                min-height: 300px;
                max-height: 400px;
                overflow-y: auto;
                color: var(--primary-color);
            }
            
            .terminal-line {
                margin-bottom: 0.5rem;
                line-height: 1.4;
            }
            
            .terminal-input-line {
                padding: 0.5rem 1rem;
                background: #0a0a0a;
                border-top: 1px solid #333;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .prompt {
                color: var(--secondary-color);
                font-weight: bold;
            }
            
            .terminal-input {
                flex: 1;
                background: transparent;
                border: none;
                color: var(--primary-color);
                font-family: 'Courier New', monospace;
                font-size: 1rem;
                outline: none;
            }
            
            .command {
                color: #fff;
            }
            
            .success {
                color: var(--primary-color);
            }
            
            .error {
                color: var(--error-color);
            }
            
            .info {
                color: #93c5fd;
            }
            
            .warning {
                color: var(--warning-color);
            }
        `;
        document.head.appendChild(style);
    }

    handleKeyPress(e) {
        if (e.key === 'Enter') {
            const command = this.input.value.trim();
            if (command) {
                this.executeCommand(command);
                this.history.push(command);
                this.historyIndex = this.history.length;
            }
            this.input.value = '';
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (this.historyIndex > 0) {
                this.historyIndex--;
                this.input.value = this.history[this.historyIndex];
            }
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (this.historyIndex < this.history.length - 1) {
                this.historyIndex++;
                this.input.value = this.history[this.historyIndex];
            } else {
                this.historyIndex = this.history.length;
                this.input.value = '';
            }
        } else if (e.key === 'Tab') {
            e.preventDefault();
            this.autoComplete();
        }
    }

    executeCommand(cmd) {
        this.writeLine(`<span class="prompt">root@security:~#</span> <span class="command">${cmd}</span>`);

        const [command, ...args] = cmd.split(' ');

        if (this.commands[command]) {
            this.commands[command](args);
        } else {
            this.writeLine(`<span class="error">Command not found: ${command}</span>`);
            this.writeLine(`<span class="info">Type 'help' for available commands</span>`);
        }
    }

    autoComplete() {
        const partial = this.input.value.toLowerCase();
        const matches = Object.keys(this.commands).filter(cmd => cmd.startsWith(partial));

        if (matches.length === 1) {
            this.input.value = matches[0];
        } else if (matches.length > 1) {
            this.writeLine(`<span class="info">Possible commands: ${matches.join(', ')}</span>`);
        }
    }

    writeLine(html) {
        const line = document.createElement('div');
        line.className = 'terminal-line';
        line.innerHTML = html;
        this.output.appendChild(line);
        this.output.scrollTop = this.output.scrollHeight;
    }

    clear() {
        this.output.innerHTML = '';
        this.writeLine(`<span class="success">Terminal cleared</span>`);
    }

    showHelp() {
        this.writeLine(`<span class="success">Available Commands:</span>`);
        this.writeLine(`  <span class="info">help</span>      - Show this help message`);
        this.writeLine(`  <span class="info">clear</span>     - Clear terminal screen`);
        this.writeLine(`  <span class="info">xss</span>       - Demonstrate XSS attacks`);
        this.writeLine(`  <span class="info">sql</span>       - SQL Injection examples`);
        this.writeLine(`  <span class="info">csrf</span>      - CSRF attack demo`);
        this.writeLine(`  <span class="info">scanner</span>   - Run vulnerability scanner`);
        this.writeLine(`  <span class="info">payloads</span>  - Show attack payloads`);
        this.writeLine(`  <span class="info">about</span>     - About this terminal`);
    }

    demonstrateXSS() {
        this.writeLine(`<span class="warning">[*] Starting XSS Demonstration...</span>`);
        setTimeout(() => {
            this.writeLine(`<span class="success">[+] Testing: &lt;script&gt;alert('XSS')&lt;/script&gt;</span>`);
        }, 500);
        setTimeout(() => {
            this.writeLine(`<span class="success">[+] Testing: &lt;img src=x onerror=alert(1)&gt;</span>`);
        }, 1000);
        setTimeout(() => {
            this.writeLine(`<span class="info">[i] Protection: Use htmlspecialchars() in PHP</span>`);
        }, 1500);
    }

    demonstrateSQL() {
        this.writeLine(`<span class="warning">[*] SQL Injection Attack Vectors:</span>`);
        setTimeout(() => {
            this.writeLine(`<span class="success">[+] ' OR '1'='1</span>`);
        }, 300);
        setTimeout(() => {
            this.writeLine(`<span class="success">[+] ' UNION SELECT * FROM users--</span>`);
        }, 600);
        setTimeout(() => {
            this.writeLine(`<span class="success">[+] '; DROP TABLE users;--</span>`);
        }, 900);
        setTimeout(() => {
            this.writeLine(`<span class="info">[i] Protection: Use prepared statements</span>`);
        }, 1200);
    }

    demonstrateCSRF() {
        this.writeLine(`<span class="warning">[*] CSRF Attack Demonstration</span>`);
        this.writeLine(`<span class="info">[i] Malicious form submits without user knowledge</span>`);
        this.writeLine(`<span class="success">[+] Protection: CSRF tokens required</span>`);
    }

    runScanner() {
        this.writeLine(`<span class="warning">[*] Initializing Vulnerability Scanner...</span>`);
        const vulnerabilities = [
            'Checking for XSS vulnerabilities...',
            'Testing SQL Injection points...',
            'Analyzing CSRF protection...',
            'Scanning for Path Traversal...',
            'Checking file upload security...'
        ];

        vulnerabilities.forEach((msg, i) => {
            setTimeout(() => {
                this.writeLine(`<span class="info">[~] ${msg}</span>`);
                if (i === vulnerabilities.length - 1) {
                    setTimeout(() => {
                        this.writeLine(`<span class="success">[✓] Scan complete! Found ${Math.floor(Math.random() * 5)} vulnerabilities</span>`);
                    }, 500);
                }
            }, i * 800);
        });
    }

    showPayloads() {
        this.writeLine(`<span class="success">Common Attack Payloads:</span>`);
        this.writeLine(`<span class="info">XSS: &lt;script&gt;alert(document.cookie)&lt;/script&gt;</span>`);
        this.writeLine(`<span class="info">SQL: ' OR 1=1--</span>`);
        this.writeLine(`<span class="info">Path: ../../etc/passwd</span>`);
        this.writeLine(`<span class="info">Cmd: ; ls -la</span>`);
    }

    showAbout() {
        this.writeLine(`<span class="success">═══════════════════════════════════════</span>`);
        this.writeLine(`<span class="success">  CYBER SECURITY TERMINAL v2.0</span>`);
        this.writeLine(`<span class="success">═══════════════════════════════════════</span>`);
        this.writeLine(`<span class="info">Developed by: Amin Davodian</span>`);
        this.writeLine(`<span class="info">Website: https://senioramin.com</span>`);
        this.writeLine(`<span class="info">GitHub: github.com/SeniorAminam</span>`);
        this.writeLine(`<span class="success">═══════════════════════════════════════</span>`);
    }
}

// Auto-initialize if terminal container exists
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('cyber-terminal')) {
        window.terminal = new CyberTerminal('cyber-terminal');
    }
});
