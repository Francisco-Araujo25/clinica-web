/* =========================================================
   SISTEMA ADMIN — CLÍNICA
   scripts.js
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {

  /* ===============================
     CONFIRMAÇÃO DE EXCLUSÃO
     =============================== */
  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
      const msg = btn.dataset.confirm || 'Tem certeza?';
      if (!confirm(msg)) e.preventDefault();
    });
  });

  /* ===============================
     MÁSCARA CPF
     =============================== */
  document.querySelectorAll('input[name="cpf"]').forEach(input => {
    input.addEventListener('input', () => {
      let v = input.value.replace(/\D/g, '').slice(0, 11);
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      input.value = v;
    });
  });

  /* ===============================
     MÁSCARA TELEFONE
     =============================== */
  document.querySelectorAll('input[name="telefone"]').forEach(input => {
    input.addEventListener('input', () => {
      let v = input.value.replace(/\D/g, '').slice(0, 11);
      v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
      v = v.replace(/(\d)(\d{4})$/, '$1-$2');
      input.value = v;
    });
  });

  /* ===============================
     VALIDAÇÃO DE FORMULÁRIOS
     =============================== */
  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', e => {
      let valido = true;
      clearErrors(form);

      form.querySelectorAll('[required]').forEach(campo => {
        if (!campo.value.trim()) {
          valido = false;
          showError(campo, 'Campo obrigatório');
        }
      });

      if (!valido) {
        e.preventDefault();
      }
    });
  });

  /* ===============================
     DATETIME LOCAL → VALIDAÇÃO
     =============================== */
  const dataConsulta = document.querySelector('input[type="datetime-local"]');
  if (dataConsulta) {
    dataConsulta.addEventListener('change', () => {
      const agora = new Date();
      const escolhida = new Date(dataConsulta.value);
      if (escolhida < agora) {
        alert('A data da consulta não pode ser no passado.');
        dataConsulta.value = '';
      }
    });
  }

});

/* ===============================
   HELPERS
   =============================== */
function showError(input, message) {
  input.classList.add('input-error');

  const span = document.createElement('small');
  span.className = 'error-text';
  span.innerText = message;

  input.parentNode.appendChild(span);
}

function clearErrors(form) {
  form.querySelectorAll('.error-text').forEach(e => e.remove());
  form.querySelectorAll('.input-error').forEach(i => i.classList.remove('input-error'));
}

// Feedback para exclusão
document.addEventListener('DOMContentLoaded', function() {
    // Verificar parâmetros de status na URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    if (status) {
        let message = '';
        let type = 'info';
        
        switch(status) {
            case 'excluido':
                message = 'Registro excluído com sucesso!';
                type = 'success';
                break;
            case 'erro_fk':
                message = 'Não foi possível excluir. Existem consultas relacionadas.';
                type = 'danger';
                break;
            case 'nao_encontrado':
                message = 'Registro não encontrado.';
                type = 'warning';
                break;
            case 'erro_id':
                message = 'ID inválido.';
                type = 'danger';
                break;
            case 'erro_prepare':
                message = 'Erro no sistema. Tente novamente.';
                type = 'danger';
                break;
        }
        
        if (message) {
            // Criar alerta
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} mb-4`;
            alertDiv.innerHTML = `
                <h3 class="alert-title">${type === 'success' ? 'Sucesso!' : 'Atenção!'}</h3>
                <p>${message}</p>
            `;
            
            // Inserir após o header
            const main = document.querySelector('main .container');
            if (main) {
                main.insertBefore(alertDiv, main.firstChild);
            }
            
            // Remover parâmetro da URL sem recarregar
            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 3000);
        }
    }
    
    // Confirmação de exclusão aprimorada
    const deleteButtons = document.querySelectorAll('a[data-confirm], a.btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm') || 'Tem certeza que deseja excluir este registro?')) {
                e.preventDefault();
            }
        });
    });
});