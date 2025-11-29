/**
 * Project: Web Design Course Presentation - Hacker Edition
 * Author: Amin Davodian (Mohammadamin Davodian)
 * Website: https://senioramin.com
 * LinkedIn: https://linkedin.com/in/SudoAmin
 * GitHub: https://github.com/SeniorAminam
 * Created: 2025-11-24
 * 
 * Request Interceptor - Visualizes HTTP requests before submission
 * Developed by Amin Davodian
 */

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');

    // Create Modal HTML - Developed by Amin Davodian
    const modalHtml = `
        <div id="interceptor-modal">
            <div class="interceptor-content">
                <div class="interceptor-header">
                    <h2 style="margin:0">📡 NETWORK INTERCEPTOR</h2>
                    <span style="color:var(--text-muted)">CAPTURING...</span>
                </div>
                <div class="packet-anim"></div>
                <div id="request-details" style="margin: 1rem 0; color: var(--primary-color);"></div>
                <button id="btn-forward" style="width:100%">FORWARD PACKET >></button>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const modal = document.getElementById('interceptor-modal');
    const details = document.getElementById('request-details');
    const btnForward = document.getElementById('btn-forward');
    let currentForm = null;

    // Intercept all form submissions
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            currentForm = form;

            // Gather form data
            const formData = new FormData(form);
            const method = form.method.toUpperCase();
            const action = form.action || window.location.href;
            let dataStr = '';

            for (let [key, value] of formData.entries()) {
                dataStr += `[${key}]: ${value}\n`;
            }

            // Display intercepted data
            details.innerHTML = `
                <p><strong>METHOD:</strong> ${method}</p>
                <p><strong>TARGET:</strong> ${action}</p>
                <hr style="border-color:#333; margin: 10px 0;">
                <p><strong>PAYLOAD:</strong></p>
                <pre>${dataStr || '(Empty)'}</pre>
            `;

            modal.style.display = 'flex';
        });
    });

    // Forward button handler
    btnForward.addEventListener('click', () => {
        if (currentForm) {
            modal.style.display = 'none';
            currentForm.submit();
        }
    });
});
