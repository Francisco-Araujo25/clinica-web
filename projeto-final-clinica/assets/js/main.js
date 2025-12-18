
/* assets/js/main.js
   Clínica Moderna — Script principal da Home
   Autor: FRANCISCO | Data: 2025-12-17
*/

// ============================
// Configurações da aplicação
// ============================
const APP_CONFIG = {
  whatsappDDI: '55',          // Brasil
  whatsappDDD: '43',          // Londrina
  whatsappNumber: '999999999',// ajuste para seu número real (sem pontuação)
  // URL de destino quando preferir fluxo por página PHP
  cadastrarConsultaURL: '/consultas/cadastrar_consulta.php',
  // Endpoint opcional para listar médicos em JSON
  medicosApiURL: '/medicos/listar_api.php', // você pode criar este endpoint
  // Mapeamento de especialidades (slug -> nome)
  especialidades: {
    'clinica-geral': 'Clínica Geral',
    'pediatria': 'Pediatria',
    'cardiologia': 'Cardiologia',
    'dermatologia': 'Dermatologia',
    'ginecologia': 'Ginecologia',
    'ortopedia': 'Ortopedia'
  }
};

// ============================
// Utilitários
// ============================
const Utils = (() => {
  const clamp = (v, min, max) => Math.min(Math.max(v, min), max);

  const debounce = (fn, delay = 200) => {
    let t = null;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), delay);
    };
  };

  const smoothScrollTo = (selector, offset = 0) => {
    const el = document.querySelector(selector);
    if (!el) return;
    const y = el.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top: y, behavior: 'smooth' });
  };

  const onlyDigits = (str) => (str || '').replace(/\D/g, '');

  const formatTelDisplay = (digits) => {
    const d = onlyDigits(digits);
    if (d.length <= 10) {
      // (43) 3333-3333
      return d.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    }
    // (43) 99999-9999
    return d.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
  };

  // Converte "YYYY-MM-DDTHH:MM" -> "YYYY-MM-DD HH:MM:SS"
  const datetimeLocalToMySQL = (val) => {
    if (!val) return null;
    const base = val.replace('T', ' ');
    return base.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/)
      ? base + ':00'
      : base;
  };

  // Normaliza nome (capitular)
  const toTitleCase = (s) =>
    (s || '')
      .toLowerCase()
      .split(' ')
      .map(w => w.charAt(0).toUpperCase() + w.slice(1))
      .join(' ');

  return {
    clamp, debounce, smoothScrollTo, onlyDigits,
    formatTelDisplay, datetimeLocalToMySQL, toTitleCase
  };
})();

// ============================
// Toast (feedback visual)
// ============================
const Toast = (() => {
  let container = null;

  const ensure = () => {
    if (!container) {
      container = document.createElement('div');
      container.setAttribute('aria-live', 'polite');
      container.style.position = 'fixed';
      container.style.right = '16px';
      container.style.bottom = '16px';
      container.style.zIndex = '9999';
      document.body.appendChild(container);
    }
  };

  const show = (message, type = 'info', timeout = 3000) => {
    ensure();
    const bg = {
      info:    '#00bcd4',
      success: '#2ecc71',
      warning: '#f5a623',
      danger:  '#e53935'
    }[type] || '#333';

    const el = document.createElement('div');
    el.role = 'alert';
    el.style.background = bg;
    el.style.color = '#fff';
    el.style.padding = '12px 16px';
    el.style.marginTop = '8px';
    el.style.borderRadius = '8px';
    el.style.boxShadow = '0 8px 24px rgba(0,0,0,.12)';
    el.style.fontFamily = 'Poppins, system-ui, sans-serif';
    el.textContent = message;
    container.appendChild(el);

    setTimeout(() => {
      el.style.opacity = '0';
      el.style.transition = 'opacity .2s ease';
      setTimeout(() => el.remove(), 200);
    }, timeout);
  };

  return { show };
})();

// ============================
// Menu móvel + acessibilidade
// ============================
const Nav = (() => {
  let navBtn, navDrawer, lastFocus;

  const trapFocus = (container) => {
    const focusables = container.querySelectorAll(
      'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
    );
    if (!focusables.length) return;

    const first = focusables[0];
    const last  = focusables[focusables.length - 1];

    const handler = (e) => {
      if (e.key !== 'Tab') return;
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault(); last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault(); first.focus();
      }
    };
    container.addEventListener('keydown', handler);
    container._trapHandler = handler;
  };

  const open = () => {
    lastFocus = document.activeElement;
    navDrawer.classList.add('is-open');
    navDrawer.setAttribute('aria-hidden', 'false');
    trapFocus(navDrawer);
    // foco no primeiro link
    const firstLink = navDrawer.querySelector('a, button');
    if (firstLink) firstLink.focus();
    document.body.style.overflow = 'hidden';
  };

  const close = () => {
    navDrawer.classList.remove('is-open');
    navDrawer.setAttribute('aria-hidden', 'true');
    if (navDrawer._trapHandler) {
      navDrawer.removeEventListener('keydown', navDrawer._trapHandler);
      navDrawer._trapHandler = null;
    }
    if (lastFocus) lastFocus.focus();
    document.body.style.overflow = '';
  };

  const init = () => {
    navBtn = document.querySelector('#navToggle');
    navDrawer = document.querySelector('#navDrawer');
    if (!navBtn || !navDrawer) return;

    navBtn.addEventListener('click', () => {
      const isOpen = navDrawer.classList.contains('is-open');
      isOpen ? close() : open();
    });

    // Fecha ao clicar fora
    navDrawer.addEventListener('click', (e) => {
      if (e.target === navDrawer) close();
    });

    // Fecha com Esc
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navDrawer.classList.contains('is-open')) close();
    });
  };

  return { init, open, close };
})();

// ============================
// Header fixo sombreado ao rolar
// ============================
const HeaderFX = (() => {
  const init = () => {
    const header = document.querySelector('.page-header');
    if (!header) return;
    const onScroll = Utils.debounce(() => {
      const y = window.scrollY || document.documentElement.scrollTop;
      header.style.boxShadow = y > 4 ? '0 4px 12px rgba(0,0,0,.06)' : 'none';
    }, 20);
    window.addEventListener('scroll', onScroll);
    onScroll();
  };
  return { init };
})();

// ============================
// Scroll suave para âncoras
// ============================
const SmoothAnchors = (() => {
  const init = () => {
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[href^="#"]');
      if (!a) return;
      const href = a.getAttribute('href');
      if (!href || href === '#') return;
      e.preventDefault();
      Utils.smoothScrollTo(href, 12);
      history.replaceState(null, '', href);
    });
  };
  return { init };
})();

// ============================
// Carregar lista de médicos (opcional, via API)
// ============================
const MedicosLoader = (() => {
  const init = async () => {
    const select = document.querySelector('#medico_id');
    if (!select) return;
    if (!APP_CONFIG.medicosApiURL) return;

    try {
      const resp = await fetch(APP_CONFIG.medicosApiURL, { headers: { 'Accept': 'application/json' }});
      if (!resp.ok) throw new Error('Falha ao carregar médicos');
      const data = await resp.json(); // esperados [{id_medico, nome_medico}, ...]
      // Limpa e adiciona
      select.innerHTML = '<option value="">Qualquer médico</option>';
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = Number(m.id_medico);
        opt.textContent = m.nome_medico;
        select.appendChild(opt);
      });
    } catch (err) {
      Toast.show('Não foi possível carregar a lista de médicos.', 'warning');
      // mantém opções atuais estáticas
    }
  };
  return { init };
})();

// ============================
// Form de Agendamento Rápido
// ============================
const QuickBooking = (() => {
  const init = () => {
    const form = document.querySelector('form.form[action$="cadastrar_consulta.php"], form.form[action="/consultas/cadastrar_consulta.php"], form.form[action$="_consulta.php"]') 
              || document.querySelector('section .card-body form.form');
    if (!form) return;

    const selEsp   = form.querySelector('#especialidade');
    const selMed   = form.querySelector('#medico_id');
    const dtInput  = form.querySelector('#data_consulta');
    const telInput = form.querySelector('#telefone');

    // Máscara visual do telefone
    if (telInput) {
      telInput.addEventListener('input', () => {
        const d = Utils.onlyDigits(telInput.value);
        telInput.value = Utils.formatTelDisplay(d);
      });
    }

    form.addEventListener('submit', (e) => {
      // se for GET de continuidade, deixe seguir; senão intercepta e decide
      e.preventDefault();

      const espSlug  = selEsp ? selEsp.value : '';
      const medicoId = selMed ? Number(selMed.value || 0) : 0;
      const dtLocal  = dtInput ? dtInput.value : '';
      const telRaw   = telInput ? Utils.onlyDigits(telInput.value) : '';

      const erros = [];
      if (!espSlug) erros.push('Selecione uma especialidade.');
      if (!dtLocal) erros.push('Informe a data e hora da consulta.');

      if (erros.length) {
        Toast.show(erros.join(' '), 'danger');
        return;
      }

      // Converte data/hora
      const dtMySQL = Utils.datetimeLocalToMySQL(dtLocal);

      // Estratégia: abrir WhatsApp com mensagem pronta OU redirecionar para cadastrar_consulta.php
      // 1) WHATSAPP
      if (telRaw && telRaw.length >= 10) {
        const msg = [
          'Olá! Gostaria de agendar uma consulta.',
          `Especialidade: ${APP_CONFIG.especialidades[espSlug] || Utils.toTitleCase(espSlug.replace('-', ' '))}`,
          medicoId ? `Médico ID: ${medicoId}` : 'Médico: indifere',
          `Data/Hora: ${dtMySQL || dtLocal}`,
          `Telefone: (${telRaw.slice(0,2)}) ${telRaw.slice(2)}`
        ].join('\n');

        const waNumber = APP_CONFIG.whatsappDDI + APP_CONFIG.whatsappDDD + APP_CONFIG.whatsappNumber;
        const url = `https://wa.me/${waNumber}?text=${encodeURIComponent(msg)}`;
        window.open(url, '_blank', 'noopener');
        Toast.show('Abrindo WhatsApp para finalizar o agendamento…', 'info');
        return;
      }

      // 2) REDIRECIONAR para o cadastro de consulta com query params (para pré-preencher)
      const params = new URLSearchParams({
        paciente_id: '',         // se quiser passar depois
        medico_id: medicoId || '',
        data_consulta: dtMySQL || dtLocal,
        diagnostico: '',
        tratamento: '',
        prescricao_medica: '',
        especialidade: espSlug
      });
      const url = `${APP_CONFIG.cadastrarConsultaURL}?${params.toString()}`;
      window.location.href = url;
    });
  };

  return { init };
})();

// ============================
// Inicialização
// ============================
document.addEventListener('DOMContentLoaded', () => {
  Nav.init();
  HeaderFX.init();
  SmoothAnchors.init();
  MedicosLoader.init();
  QuickBooking.init();

  // Anexa eventos aos botões de navegação principais (se existirem)
  const ctaAgendar = document.querySelectorAll('[data-cta="agendar"]');
  ctaAgendar.forEach(btn => btn.addEventListener('click', (e) => {
    e.preventDefault();
    Utils.smoothScrollTo('#agendar', 12);
  }));
});