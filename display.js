document.addEventListener('DOMContentLoaded', function() {
   
    document.querySelectorAll('.complete-habit').forEach(button => {
        button.addEventListener('click', function() {
            const habitId = this.dataset.id;
            fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=complete&id=${habitId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = this.textContent === 'Complete' ? 'Undo' : 'Complete';
                }
            });
        });
    });

    document.querySelectorAll('.edit-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'edit');

            fetch('index.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update habit: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating habit');
            });
        });
    });


    document.querySelectorAll('.button-edit').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const habitItem = this.closest('.habit-item');
            const habitInfo = habitItem.querySelector('.habit-info');
            const editForm = habitItem.querySelector('.edit-form');
            
            document.querySelectorAll('.edit-form').forEach(form => {
                form.style.display = 'none';
                form.previousElementSibling.style.display = 'flex';
            });

        
            if (editForm && habitInfo) {
                habitInfo.style.display = 'none';
                editForm.style.display = 'block';
            }
        });
    });

    document.querySelectorAll('.button-cancel').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const habitItem = this.closest('.habit-item');
            const habitInfo = habitItem.querySelector('.habit-info');
            const editForm = habitItem.querySelector('.edit-form');
            
            if (editForm && habitInfo) {
                editForm.style.display = 'none';
                habitInfo.style.display = 'flex';
            }
        });
    });

   
    document.querySelectorAll('.delete-habit').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this habit?')) {
                const habitId = this.dataset.id;
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=delete&id=${habitId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.habit-item').remove();
                    } else {
                        alert('Failed to delete habit: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting habit: ' + error.message);
                });
            }
        });
    });
});