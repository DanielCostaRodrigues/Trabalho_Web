// Aguarda o carregamento completo do DOM antes de executar o código
document.addEventListener("DOMContentLoaded", () => {
  // Variável geral para guardar o tamanho selecionado
  let selectedSize = null;

  // Referenciar o ícone do menu e o dropdown menu
  const menuIcon = document.getElementById("menu"); // Ícone do menu
  const dropdownMenu = document.getElementById("dropdownMenu"); // Menu dropdown

  // Função para alternar a visibilidade do menu dropdown
  function toggleDropdownMenu() {
    dropdownMenu.classList.toggle("open"); // Adiciona ou remove a classe "open" para mostrar/ocultar o menu
  }

  // Abre e fecha o dropdown ao clicar no ícone
  if (menuIcon) {
    menuIcon.addEventListener("click", (event) => {
      event.stopPropagation(); // Prevenir o evento de fechar o menu ao clicar no ícone
      toggleDropdownMenu();
    });
  }

  // Fecha o menu ao clicar fora dele
  document.addEventListener("click", (event) => {
    if (
      dropdownMenu &&
      !dropdownMenu.contains(event.target) && // Verifica se o clique não foi no menu
      event.target !== menuIcon // Verifica se o clique não foi no ícone do menu
    ) {
      dropdownMenu.classList.remove("open"); // Remove a classe "open" para fechar o menu
    }
  });

  // Funções relacionadas ao modal para redefinir a pass
  const modal = document.getElementById("passwordModal"); // Modal de redefinição
  const link = document.getElementById("forgotPasswordLink"); // Link para abrir o modal
  const closeModal = document.querySelector(".close"); // Botão para fechar o modal
  const cancelButton = document.getElementById("cancelButton"); // Botão de cancelar
  const resetPasswordForm = document.getElementById("resetPasswordForm"); // Formulário de redefinição
  const successMessage = document.getElementById("successMessage"); // Mensagem de sucesso
  const emailMessage = document.getElementById("emailMessage"); // Mensagem de validação do email
  const resetEmailInput = document.getElementById("resetEmail"); // Campo de email no modal

  // Evento para abrir o modal para redefinir a pass
  if (link) {
    link.addEventListener("click", (event) => {
      event.preventDefault(); // Evita o comportamento padrão do link
      modal.style.display = "block"; // Exibe o modal
      successMessage.style.display = "none"; // Oculta a mensagem de sucesso
      resetEmailInput.value = ""; // Limpa o campo de email
      emailMessage.textContent = ""; // Limpa a mensagem de validação
    });
  }

  // Fecha o modal ao clicar no botão de fechar
  if (closeModal) {
    closeModal.addEventListener("click", () => {
      modal.style.display = "none"; // Oculta o modal
    });
  }

  // Fecha o modal ao clicar no botão de cancelar
  if (cancelButton) {
    cancelButton.addEventListener("click", () => {
      modal.style.display = "none"; // Oculta o modal
    });
  }

  // Validação do email digitado no modal
  if (resetEmailInput) {
    resetEmailInput.addEventListener("input", () => {
      const emailValue = resetEmailInput.value; // Captura o valor do email
      if (emailValue.includes("@") && emailValue.includes(".")) {
        emailMessage.textContent =
          "O email parece estar bem! Enviaremos o link de recuperação em breve.";
        emailMessage.style.color = "green"; // Exibe mensagem de validação positiva
      } else if (emailValue === "") {
        emailMessage.textContent = ""; // Limpa a mensagem se o campo estiver vazio
      } else {
        emailMessage.textContent = "Por favor, insira um email válido."; // Exibe mensagem de erro
        emailMessage.style.color = "red";
      }
    });
  }

  // Submete o formulário para redifinir a pass
  if (resetPasswordForm) {
    resetPasswordForm.addEventListener("submit", (event) => {
      event.preventDefault();
      successMessage.textContent =
        "Um link de recuperação foi enviado para o seu email!";
      successMessage.style.display = "block";
      resetPasswordForm.reset();
      emailMessage.textContent = "";

      // Fechar o modal após 3 segundos
      setTimeout(() => {
        modal.style.display = "none";
        successMessage.style.display = "none";
      }, 3000);
    });
  }
});

function toggleUserDropdown() {
  const dropdown = document.getElementById("user-dropdown");
  dropdown.style.display =
    dropdown.style.display === "block" ? "none" : "block";
}

// Fecha o dropdown se clicar fora dele
window.addEventListener("click", function (event) {
  const dropdown = document.getElementById("user-dropdown");
  const userName = document.getElementById("user-name");
  if (
    dropdown &&
    userName &&
    !dropdown.contains(event.target) &&
    event.target !== userName
  ) {
    dropdown.style.display = "none";
  }
});

// Configurações para o histórico de compras
let currentPage = 1; // Página atual para "Carregar Mais"
const ordersPerPage = 1; // Carrega uma compra por vez

// Função para carregar mais pedidos
function loadOrders() {
  fetch(`load_orders.php?page=${currentPage}&per_page=${ordersPerPage}`)
    .then((response) => response.text())
    .then((data) => {
      const ordersContainer = document.getElementById("orders-container");
      if (data.trim() === "") {
        // Se não houver mais pedidos para carregar, oculta o botão "Carregar Mais"
        const loadMoreButton = document.getElementById("load-more-orders");
        if (loadMoreButton) {
          loadMoreButton.style.display = "none";
        }
      } else {
        // Adiciona os pedidos carregados ao final
        ordersContainer.innerHTML += data;
      }
    })
    .catch((err) => {
      console.error("Erro ao carregar pedidos:", err);
    });
  currentPage++;
}

// Função que será chamada pelo atributo onload do HTML
function initializeProfilePage() {
  // Carrega os pedidos assim que a página é carregada
  loadOrders();

  // Adiciona o evento ao botão "Carregar Mais"
  const loadMoreButton = document.getElementById("load-more-orders");
  if (loadMoreButton) {
    loadMoreButton.addEventListener("click", loadOrders);
  }

  console.log("Página de perfil inicializada com sucesso!");
}

// Função para selecionar o tamanho
function selectSize(button) {
  const sizeButtons = document.querySelectorAll(".size-button");
  sizeButtons.forEach((btn) => btn.classList.remove("selected"));
  button.classList.add("selected");
}

//Função adicionar ao carrinho
function addToCart() {
  const productId = JSON.parse(
    document.getElementById("product-id").textContent
  ); // ID do produto
  const selectedSize = document.querySelector(
    ".size-button.selected"
  )?.innerText; // Tamanho selecionado

  if (!selectedSize) {
    alert("Por favor, selecione um tamanho antes de adicionar ao carrinho.");
    return;
  }

  // Envia uma solicitação ao backend para adicionar o item ao carrinho
  fetch("../carrinho/update_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ productId, size: selectedSize, quantity: 1 }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        location.reload(); // Atualiza a página
      } else {
        alert(data.message || "Erro ao adicionar ao carrinho.");
      }
    })
    .catch(() => alert("Erro ao processar sua solicitação."));
}

//Função remover do carrinho
function removeFromCart(cartId) {
  const inputField = document.getElementById(`quantity-to-remove-${cartId}`); // Campo de quantidade a remover
  const quantityToRemove = parseInt(inputField.value);

  if (isNaN(quantityToRemove) || quantityToRemove <= 0) {
    alert("Por favor, insira uma quantidade válida para remover.");
    return;
  }

  // Envia solicitação ao backend para remover o produto
  fetch("../carrinho/remove_from_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ cartId: cartId, quantity: quantityToRemove }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Produto removido com sucesso.");
        location.reload(); // Atualiza a página
      } else {
        alert(data.message || "Erro ao remover o Produto do carrinho.");
      }
    })
    .catch(() => alert("Erro ao processar sua solicitação."));
}

//Função atualizar total carrinho
function updateTotal() {
  let total = 0;

  // Iterar pelos itens do carrinho e somar os preços
  document.querySelectorAll(".cart-item").forEach((item) => {
    const price = parseFloat(
      item
        .querySelector(".cart-item-details p:nth-child(2)")
        .textContent.replace("Preço: ", "")
        .replace("€", "")
        .replace(",", ".")
    );
    const quantity = parseInt(item.querySelector(".quantity").textContent);
    total += price * quantity;
  });

  // Atualizar o total com apenas um símbolo de euro
  document.getElementById("cart-total").textContent = `${total
    .toFixed(2)
    .replace(".", ",")} €`;
}

// Carregar comentários
const productId = JSON.parse(document.getElementById("product-id").textContent);
const commentsPerPage = 5;

function loadComments(page = 1) {
  fetch(
    `../detalhe_produtos/get_reviews.php?product_id=${productId}&page=${page}&comments_per_page=${commentsPerPage}`
  )
    .then((response) => response.json())
    .then((data) => {
      const commentsList = document.getElementById("comments-list");
      const pagination = document.getElementById("pagination");
      commentsList.innerHTML = "";
      pagination.innerHTML = "";

      if (data.error) {
        commentsList.innerHTML = "<p>" + data.error + "</p>";
        return;
      }

      if (data.comments.length === 0) {
        commentsList.innerHTML =
          "<p>Seja o primeiro a comentar este produto.</p>";
        return;
      }

      data.comments.forEach((comment) => {
        const commentElement = document.createElement("div");
        commentElement.classList.add("comment");
        commentElement.innerHTML = `
                    <p><strong>${comment.username}</strong> (${new Date(
          comment.created_at
        ).toLocaleString()}):</p>
                    <p>${comment.comment}</p>
                `;
        commentsList.appendChild(commentElement);
      });

      // Criar paginação
      for (let i = 1; i <= data.totalPages; i++) {
        const pageLink = document.createElement("button");
        pageLink.innerText = i;
        pageLink.classList.add("page-button");
        if (i === page) pageLink.classList.add("active");
        pageLink.addEventListener("click", () => loadComments(i));
        pagination.appendChild(pageLink);
      }
    })
    .catch(() => {
      const commentsList = document.getElementById("comments-list");
      commentsList.innerHTML = "<p>Erro ao carregar comentários.</p>";
    });
}

// Inicializar comentários
document.addEventListener("DOMContentLoaded", () => loadComments());

// Adicionar comentário
document
  .getElementById("add-comment-form")
  ?.addEventListener("submit", function (e) {
    e.preventDefault();

    const commentInput = document.getElementById("comment");
    const comment = commentInput.value.trim();

    if (!comment) {
      alert("O comentário não pode estar vazio.");
      return;
    }

    fetch("../detalhe_produtos/add_comment.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ product_id: productId, comment }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.message);
          commentInput.value = "";
          loadComments();
        } else {
          alert(data.message || "Erro ao adicionar o comentário.");
        }
      })
      .catch(() => alert("Erro ao processar sua solicitação."));
  });

function toggleAddressInput(show) {
  const newAddressInput = document.getElementById("new_address");
  newAddressInput.style.display = show ? "block" : "none";
  if (!show) newAddressInput.value = "";
}

function toggleUserMenu() {
  const userMenu = document.getElementById("user-menu");
  if (userMenu.style.display === "block") {
    userMenu.style.display = "none";
  } else {
    userMenu.style.display = "block";
  }
}

// Fechar menu se clicar fora dele
document.addEventListener("click", function (event) {
  const userMenu = document.getElementById("user-menu");
  const userName = document.getElementById("user-name");
  if (!userMenu.contains(event.target) && event.target !== userName) {
    userMenu.style.display = "none";
  }
});
