// generate-pdf.js
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.generate-pdf').forEach(button => {

        button.addEventListener('click', async () => {
            console.log('Current wp_vars:', wp_vars); // Debug nonce
            console.log('Nonce being sent:', wp_vars.nonce);
            const postId = button.dataset.postId;
            button.disabled = true;

            try {
                console.log('[DEBUG] Initiating PDF generation for Post ID:', postId);

                const response = await fetch(wp_vars.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'generate_pdf',
                        post_id: postId,
                        nonce: cpdfVars.nonce,
                    }),
                });

                console.log('[DEBUG] Server response status:', response.status);

                const data = await response.json();
                console.log('[DEBUG] Server response data:', data);

                if (!response.ok) {
                    throw new Error(data.data?.message || `HTTP Error: ${response.status}`);
                }

                if (data.success) {
                    // window.open(data.data.pdf_url, '_blank');
                    alert('PDF generated successfully!');

                } else {
                    alert('Error: ' + (data.data?.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('[ERROR] Fetch failed:', error.message, error.stack);
                alert('Failed to generate PDF: ' + error.message);
            } finally {
                button.disabled = false;
            }
        });
    });
});