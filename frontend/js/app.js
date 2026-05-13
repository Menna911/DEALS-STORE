const API_BASE = 'http://localhost:8000/api';


let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
let userFavorites = []; // Array to hold the IDs of the user's favorite offers


document.addEventListener('DOMContentLoaded', () => {
    initApp();
});


async function initApp() 
{
    updateAuthUI();

    
    if (currentUser) 
    {
        await fetchUserFavorites();
    }

    
    if (document.getElementById('offers-container')) 
    {
        loadOffers();
    }
}


function updateAuthUI() 
{
    const loginLink = document.getElementById('nav-login');
    const registerLink = document.getElementById('nav-register');
    const logoutBtn = document.getElementById('nav-logout');
    const welcomeMsg = document.getElementById('nav-welcome');

    if (currentUser) 
    {
        // User IS logged in
        if (loginLink) loginLink.style.display = 'none';
        if (registerLink) registerLink.style.display = 'none';
        if (logoutBtn) logoutBtn.style.display = 'inline-block';
        if (welcomeMsg) 
        {
            welcomeMsg.style.display = 'inline-block';
            welcomeMsg.innerText = `Welcome, ${currentUser.name}!`;
        }
    } 
    else 
    {
        
        if (loginLink) loginLink.style.display = 'inline-block';
        if (registerLink) registerLink.style.display = 'inline-block';
        if (logoutBtn) logoutBtn.style.display = 'none';
        if (welcomeMsg) welcomeMsg.style.display = 'none';
    }
}


async function loadOffers() 
{
    const container = document.getElementById('offers-container');
    container.innerHTML = '<p>Loading deals...</p>';

    try 
    {
        const response = await fetch(`${API_BASE}/offers.php`);
        const result = await response.json();

        if (result.data && result.data.length > 0) {
            container.innerHTML = ''; 
            
            result.data.forEach(offer => {
                
                const isFavorited = userFavorites.includes(offer.id);
                const heartClass = isFavorited ? 'favorited' : '';

                
                const card = document.createElement('div');
                card.className = 'offer-card';
                card.innerHTML = `
                    <img src="${offer.image_url}" alt="${offer.title}" style="width:100%; height:200px; object-fit:cover;">
                    <div class="offer-details">
                        <h3>${offer.title}</h3>
                        <p>${offer.description}</p>
                        <p class="category">${offer.category}</p>
                        <div class="price-box">
                            <span class="old-price">$${offer.old_price}</span>
                            <span class="final-price">$${offer.final_price}</span>
                            <span class="discount badge">-${offer.discount_percentage}%</span>
                        </div>
                        <button class="favorite-btn ${heartClass}" onclick="toggleFavorite(${offer.id}, this)">
                            ${isFavorited ? '❤️ Saved' : '🤍 Save Deal'}
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        } 
        else 
        {
            container.innerHTML = '<p>No deals available right now.</p>';
        }
    } 
    catch (error) 
    {
        console.error('Error fetching offers:', error);
        container.innerHTML = '<p>Failed to load deals. Please try again later.</p>';
    }
}


async function fetchUserFavorites() 
{
    try 
    {
        const response = await fetch(`${API_BASE}/favorites.php?user_id=${currentUser.id}`);
        const result = await response.json();
        
        if (result.data) 
        {
            userFavorites = result.data.map(offer => offer.id);
        } 
        else 
        {
            userFavorites = [];
        }
    } 
    catch (error) 
    {
        console.error('Error fetching favorites:', error);
    }
}

async function toggleFavorite(offerId, buttonElement) 
{
    if (!currentUser) 
    {
        alert("Please log in to save your favorite deals!");
        return;
    }

    const isCurrentlyFavorited = userFavorites.includes(offerId);
    const action = isCurrentlyFavorited ? 'remove' : 'add';

    try 
    {
        const response = await fetch(`${API_BASE}/favorites.php`, 
        {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: action,
                user_id: currentUser.id,
                offer_id: offerId
            })
        });

        const result = await response.json();

        if (result.success) 
        {
            if (action === 'add') 
            {
                userFavorites.push(offerId); // Add to local array
                buttonElement.innerHTML = '❤️ Saved';
                buttonElement.classList.add('favorited');
            } 
            else 
            {
                userFavorites = userFavorites.filter(id => id !== offerId); // Remove from local array
                buttonElement.innerHTML = '🤍 Save Deal';
                buttonElement.classList.remove('favorited');
            }
        } 
        else 
        {
            alert(result.message);
        }
    } 
    catch (error) 
    {
        console.error('Error toggling favorite:', error);
    }
}

async function handleLogin(event) 
{
    event.preventDefault(); 

    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    try 
    {
        const response = await fetch(`${API_BASE}/auth.php`, 
        {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'login',
                email: email,
                password: password
            })
        });

        const result = await response.json();

        if (result.success) 
        {
            localStorage.setItem('currentUser', JSON.stringify(result.user));
            currentUser = result.user;
            alert('Login successful!');
            window.location.href = 'index.html'; // Redirect to home page
        } 
        else 
        {
            alert(result.message); 
        }
    } 
    catch (error) 
    {
        console.error('Login error:', error);
    }
}

function handleLogout() 
{
    localStorage.removeItem('currentUser');
    currentUser = null;
    userFavorites = [];
    alert('You have been logged out.');
    window.location.reload(); 
}

const loginForm = document.getElementById('login-form');
if (loginForm) 
{
    loginForm.addEventListener('submit', handleLogin);
}

