document.addEventListener('DOMContentLoaded', () => {
    // Variável geral guarda o tamanho 
    let selectedSize = null;

    // Referenciar o ícone do menu e o dropdown menu
    const menuIcon = document.getElementById('menu');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Função para alternar o menu dropdown
    function toggleDropdownMenu() {
        dropdownMenu.classList.toggle('open');
    }

    // Adicionar evento de clique no ícone do menu
    if (menuIcon) {
        menuIcon.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevenir o evento de fechar o menu ao clicar no ícone
            toggleDropdownMenu();
        });
    }

    // Fechar o menu ao clicar fora dele
    document.addEventListener('click', (event) => {
        if (dropdownMenu && !dropdownMenu.contains(event.target) && event.target !== menuIcon) {
            dropdownMenu.classList.remove('open');
        }
    });

    // Funções relacionadas ao modal de redefinição da pass
    const modal = document.getElementById("passwordModal");
    const link = document.getElementById("forgotPasswordLink");
    const closeModal = document.querySelector(".close");
    const cancelButton = document.getElementById("cancelButton");
    const resetPasswordForm = document.getElementById("resetPasswordForm");
    const successMessage = document.getElementById("successMessage");
    const emailMessage = document.getElementById("emailMessage");
    const resetEmailInput = document.getElementById("resetEmail");

    // Código do menu hambúrguer (nav-links) existente
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1023) {
                navLinks.classList.remove('open');
            }
        });
    }

    // Função de pesquisa
    const searchButton = document.querySelector('.search-bar button');
    if (searchButton) {
        searchButton.addEventListener('click', () => {
            const searchInput = document.querySelector('.search-bar input').value;
            alert(`Você pesquisou por: ${searchInput}`);
        });
    }

    // Funções relacionadas ao modal de redefinição da pass
    if (link) {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            modal.style.display = "block";
            successMessage.style.display = "none";
            resetEmailInput.value = "";
            emailMessage.textContent = "";
        });
    }

    if (closeModal) {
        closeModal.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    if (resetEmailInput) {
        resetEmailInput.addEventListener("input", () => {
            const emailValue = resetEmailInput.value;
            if (emailValue.includes("@") && emailValue.includes(".")) {
                emailMessage.textContent = "O email parece estar bem! Enviaremos o link de recuperação em breve.";
                emailMessage.style.color = "green";
            } else if (emailValue === "") {
                emailMessage.textContent = "";
            } else {
                emailMessage.textContent = "Por favor, insira um email válido.";
                emailMessage.style.color = "red";
            }
        });
    }

    if (resetPasswordForm) {
        resetPasswordForm.addEventListener("submit", (event) => {
            event.preventDefault();
            successMessage.textContent = "Um link de recuperação foi enviado para o seu email!";
            successMessage.style.display = "block";
            resetPasswordForm.reset();
            emailMessage.textContent = "";

            setTimeout(() => {
                modal.style.display = "none";
                successMessage.style.display = "none";
            }, 3000);
        });
    }

    // Função para selecionar um tamanho
    window.selectSize = function(button) {
        const allButtons = document.querySelectorAll('.size-options button');
        allButtons.forEach(btn => {
            btn.style.backgroundColor = 'white';
            btn.style.color = '#9e0909';
            btn.style.border = '1px solid #9e0909';
        });

        // Atualizar o estilo do botão selecionado
        button.style.backgroundColor = '#ffd700';
        button.style.color = '#8b0000';
        button.style.border = '1px solid #8b0000';

        // Atualizar o tamanho selecionado
        selectedSize = button.innerText; // Guardar o tamanho selecionado
    }

    // Carrinho de compras
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    // Atualiza o contador de produtos do cesto no cabeçalho
    function updateCartCount() {
        const cartCount = cartItems.reduce((total, item) => total + item.quantidade, 0);
        const cartElement = document.querySelector('.cart-count');
        if (cartElement) {
            cartElement.innerText = `CESTO (${cartCount})`;
        }
    }

    // Função para adicionar um produto ao cesto
    window.addToCart = function() {
        if (!selectedSize) {
            alert("Por favor, selecione um tamanho antes de adicionar ao cesto.");
            return;
        }

        const params = new URLSearchParams(window.location.search);
        const tipo = params.get('tipo');
        const productId = params.get('id');

        if (!tipo || !productId) {
            alert("Erro ao adicionar o produto. Tente novamente.");
            return;
        }

        const productName = document.querySelector('.product-detail-info h1').innerText;
        const productPrice = document.querySelector('.product-detail-info p').innerText.replace('€', '').trim();
        const productImage = document.querySelector('.product-detail-image img').src;

        const cartItem = {
            id: productId,
            tipo: tipo,
            nome: productName,
            tamanho: selectedSize,
            preco: productPrice,
            imagem: productImage
        };

        const existingItemIndex = cartItems.findIndex(item =>
            item.id === cartItem.id && item.tamanho === cartItem.tamanho && item.tipo === cartItem.tipo
        );

        if (existingItemIndex >= 0) {
            cartItems[existingItemIndex].quantidade += 1;
        } else {
            cartItem.quantidade = 1;
            cartItems.push(cartItem);
        }

        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        updateCartCount();
        alert(`O produto foi adicionado ao cesto com o tamanho: ${selectedSize}`);
    };

    // Carregar os itens do carrinho
    window.loadCartItems = function() {
        const cartItemsContainer = document.getElementById('cart-items');
        let cartTotal = 0;

        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = '';

            if (cartItems.length === 0) {
                cartItemsContainer.innerHTML = '<p>O cesto está vazio.</p>';
            } else {
                cartItems.forEach((item, index) => {
                    const itemElement = document.createElement('div');
                    itemElement.classList.add('cart-item');
                    itemElement.innerHTML = `
                        <img src="${item.imagem}" alt="${item.nome}" class="cart-item-image">
                        <div class="cart-item-info">
                            <h2>${item.nome}</h2>
                            <p>Tamanho: ${item.tamanho}</p>
                            <p>Preço: €${item.preco}</p>
                            <p>Quantidade: ${item.quantidade}</p>
                            <button onclick="removeFromCart(${index})" class="remove-button">Remover</button>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemElement);
                    cartTotal += parseFloat(item.preco.replace(',', '.')) * item.quantidade;
                });
            }

            document.getElementById('cart-total').innerText = `€${cartTotal.toFixed(2)}`;
        }
    };

    // Remover um item do carrinho
    window.removeFromCart = function(index) {
        cartItems.splice(index, 1);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        loadCartItems();
        updateCartCount();
    };

    updateCartCount();
});
