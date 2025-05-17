document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.generate-pdf').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const scheme = this.dataset.scheme || 'qms';    // e.g. data-scheme="qms"
            const stage = this.dataset.stage || 'f03';    // e.g. data-stage="f03"

            console.log('Generating PDF for', scheme, stage, 'post', postId);

            fetch(cpdf_vars.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'generate_pdf',
                    nonce: cpdf_vars.generate_pdf_nonce,
                    post_id: postId,
                    scheme: scheme,
                    stage: stage
                })
            })
                .then(r => {
                    if (!r.ok) throw new Error(`HTTP ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.data.message || 'Unknown');
                    alert('PDF Ready: ' + data.data.pdf_url);
                    button.outerHTML = `<a href="${data.data.pdf_url}" target="_blank" class="btn btn-primary btn-sm">
                              View Agreement
                            </a>`;
                })
                .catch(err => {
                    console.error(err);
                    alert('Error: ' + err.message);
                });
        });
    });
});
