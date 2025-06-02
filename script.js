document.addEventListener('DOMContentLoaded', () => {

    // Fetch Donors
    fetch('get_donors.php')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok for donors');
            return response.json();
        })
        .then(data => {
            let donorList = document.getElementById('donorList');
            if (!donorList) return;
            data.forEach(donor => {
                let li = document.createElement('li');
                li.innerHTML = `<strong>${donor.name}</strong> (${donor.blood_group}) - Contact: ${donor.contact}`;
                donorList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error fetching donors:', error);
        });

    // Fetch Posts
    fetch('get_posts.php')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok for posts');
            return response.json();
        })
        .then(data => {
            let postsDiv = document.getElementById('posts');
            if (!postsDiv) return;
            data.forEach(post => {
                let postDiv = document.createElement('div');
                postDiv.innerHTML = `<img src="${post.image}" width="200" alt="Post Image"><p>${post.text}</p>`;
                postsDiv.appendChild(postDiv);
            });
        })
        .catch(error => {
            console.error('Error fetching posts:', error);
        });

    // Slideshow
    let slideIndex = 0;
    function showSlides() {
        let slides = document.getElementsByClassName("slide");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1; }
        if (slides.length > 0) {
            slides[slideIndex - 1].style.display = "flex";
        }
        setTimeout(showSlides, 5000);
    }
    showSlides();

    // Responsive Navigation Menu Toggle
    const menuToggle = document.getElementById('menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            const navMenu = document.getElementById('nav-menu');
            if (navMenu) {
                navMenu.classList.toggle('active');
            }
        });
    }
});
