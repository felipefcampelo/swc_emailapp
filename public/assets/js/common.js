// Initialize Quill editor
const quill = new Quill('#email-content', {
    theme: 'snow',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'color': [] }, { 'background': [] }],
        ]
    }
});

// Store the Quill editor's content in the hidden input field before submitting the form
const form = document.querySelector('form');
form.addEventListener('submit', () => {
    const emailContentInput = document.querySelector('#email-content-input');
    emailContentInput.value = quill.root.innerHTML;
});