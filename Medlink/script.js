document.addEventListener("DOMContentLoaded", function() {
    fetch('get_doctors.php')
        .then(response => response.json())
        .then(data => {
            let doctorSelect = document.querySelector("select[name='doctor_id']");
            data.forEach(doctor => {
                let option = document.createElement("option");
                option.value = doctor.id;
                option.text = doctor.name + " - " + doctor.specialization;
                doctorSelect.add(option);
            });
        });
});
