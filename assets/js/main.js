document.addEventListener("DOMContentLoaded", () => {
  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const body = document.body;
  const isFrontPageExperience = Boolean(alenuTheme?.performance?.isFrontPage);
  const animeLib = window.anime;
  const animateMotion = animeLib?.animate;
  const createMotionTimeline = animeLib?.createTimeline;
  const staggerMotion = animeLib?.stagger;
  const loader = document.querySelector(".site-loader");
  const revealItems = document.querySelectorAll(".reveal");
  const parallaxItems = document.querySelectorAll("[data-parallax]");
  const form = document.querySelector("[data-quote-form]");
  const status = document.querySelector("[data-form-status]");
  const deviceCanvases = Array.from(document.querySelectorAll("[data-device-canvas]"));
  const counters = document.querySelectorAll("[data-counter-value]");
  const worksBrowsers = Array.from(document.querySelectorAll("[data-works-browser]"));
  const blogBrowsers = Array.from(document.querySelectorAll("[data-blog-browser]"));
  const spaStage = document.querySelector("[data-spa-stage]");
  const spaPanels = Array.from(document.querySelectorAll("[data-spa-view]"));
  const spaTriggers = Array.from(document.querySelectorAll("[data-spa-trigger]"));
  const floatingNav = document.querySelector(".floating-nav");
  const floatingNavToggle = floatingNav?.querySelector(".floating-nav__toggle");
  const floatingNavLinks = floatingNav
    ? Array.from(floatingNav.querySelectorAll(".floating-nav__side a, .floating-nav__side button"))
    : [];
  const heroTitle = document.querySelector("[data-hero-title]");
  const spaRoutes = alenuTheme?.spa?.routes || {};
  const currentSpaView = alenuTheme?.spa?.currentView || "";
  const defaultView = currentSpaView || spaStage?.dataset.spaDefault || spaPanels[0]?.dataset.spaView || "home";
  const validViews = new Set(spaPanels.map((panel) => panel.dataset.spaView));
  let revealObserver;
  let currentView = "";
  let parallaxX = 0;
  let parallaxY = 0;
  let parallaxFrame = 0;
  const revealedItems = new WeakSet();
  const hasAnimeMotion = Boolean(!prefersReducedMotion && animateMotion && createMotionTimeline && staggerMotion);

  if (hasAnimeMotion) {
    body.classList.add("anime-motion-ready");
  }

  const finalizeLoader = () => {
    body.classList.add("is-loaded");
    loader?.setAttribute("aria-hidden", "true");
  };

  if (loader && hasAnimeMotion) {
    createMotionTimeline({
      defaults: {
        ease: "out(4)",
      },
      onComplete: finalizeLoader,
    })
      .add(".site-loader__pulse", {
        opacity: [0, 1],
        filter: ["blur(18px)", "blur(0px)"],
        duration: 320,
      }, 0)
      .add(".site-loader__ring", {
        scale: staggerMotion([0.92, 1.04]),
        rotate: staggerMotion(["0deg", "48deg"]),
        opacity: staggerMotion([0.18, 0.82]),
        duration: 420,
        delay: staggerMotion(40),
      }, 0)
      .add(".site-loader .brand-mark__icon", {
        opacity: [0, 1],
        scale: [0.76, 1],
        rotate: ["-18deg", "0deg"],
        duration: 300,
      }, 60)
      .add(".site-loader .brand-mark__word", {
        opacity: [0, 1],
        filter: ["blur(18px)", "blur(0px)"],
        letterSpacing: ["0.42em", "0.18em"],
        translateY: [18, 0],
        duration: 360,
      }, 90)
      .add(".site-loader__label", {
        opacity: [0, 1],
        translateY: [14, 0],
        letterSpacing: ["0.52em", "0.38em"],
        duration: 260,
      }, 140)
      .add(loader, {
        opacity: [1, 0],
        duration: 220,
        ease: "inOut(3)",
      }, 420);
  } else {
    window.setTimeout(finalizeLoader, 120);
  }

  const getRevealDelay = (element) => {
    const delay = window.getComputedStyle(element).getPropertyValue("--delay").trim();

    if (!delay) return 0;

    return delay.endsWith("ms") ? Number.parseFloat(delay) : Number.parseFloat(delay) * 1000;
  };

  const revealElement = (element) => {
    if (!element || revealedItems.has(element)) return;

    revealedItems.add(element);
    element.classList.add("is-visible");

    if (!hasAnimeMotion) {
      return;
    }

    const isScaleReveal = element.classList.contains("reveal--scale");

    animateMotion(element, {
      opacity: [0, 1],
      translateY: [isScaleReveal ? 22 : 34, 0],
      scale: isScaleReveal ? [0.94, 1] : [0.985, 1],
      filter: ["blur(16px)", "blur(0px)"],
      duration: 920,
      delay: getRevealDelay(element),
      ease: "out(4)",
    });
  };

  const animateActivePanel = (panel) => {
    if (!panel || !hasAnimeMotion) return;

    animateMotion(panel, {
      opacity: [0.22, 1],
      translateY: [22, 0],
      scale: [0.986, 1],
      duration: 760,
      ease: "out(4)",
    });
  };

  const animateHeroTitle = (force = false) => {
    if (!heroTitle || !hasAnimeMotion) return;

    const lines = Array.from(heroTitle.querySelectorAll(".hero-title__line"));
    const texts = Array.from(heroTitle.querySelectorAll(".hero-title__text"));

    if (!lines.length || !texts.length) return;
    if (!force && heroTitle.dataset.laserAnimated === "true") return;

    heroTitle.dataset.laserAnimated = "true";

    texts.forEach((text) => {
      text.style.opacity = "0";
      text.style.filter = "blur(14px)";
      text.style.clipPath = "inset(0 100% 0 0)";
    });

    lines.forEach((line) => {
      line.style.opacity = "1";
    });

    const timeline = createMotionTimeline({
      defaults: {
        ease: "out(4)",
      },
    });

    lines.forEach((line, index) => {
      const text = line.querySelector(".hero-title__text");
      const start = index * 170;

      timeline
        .add(text, {
          opacity: [0, 1],
          filter: ["blur(14px)", "blur(0px)"],
          clipPath: ["inset(0 100% 0 0)", "inset(0 0% 0 0)"],
          letterSpacing: ["-0.12em", "-0.06em"],
          duration: 860,
        }, start)
        .add(line, {
          "--laser-pass": [0, 1],
          duration: 520,
          onBegin: () => {
            line.style.setProperty("--laser-pass", "0");
            line.style.opacity = "1";
          },
          onUpdate: (anim) => {
            const progress = typeof anim.progress === "number" ? anim.progress / 100 : 0;
            line.style.setProperty("--laser-pass", String(progress));
            line.style.setProperty("opacity", "1");
            line.style.setProperty("--laser-opacity", String(Math.max(0, 1 - progress * 0.85)));
          },
          onComplete: () => {
            line.style.removeProperty("--laser-pass");
            line.style.removeProperty("--laser-opacity");
          },
        }, start + 110);
    });
  };

  if (revealItems.length) {
    revealObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            revealElement(entry.target);
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.18 }
    );

    revealItems.forEach((item) => revealObserver.observe(item));
  }

  const observeReveal = (element) => {
    if (!element) return;

    if (prefersReducedMotion) {
      revealElement(element);
      return;
    }

    if (revealObserver) {
      revealObserver.observe(element);
    } else {
      revealElement(element);
    }
  };

  const syncNavOffset = () => {
    if (!floatingNav) return;

    const navStyles = window.getComputedStyle(floatingNav);
    const navTop = Number.parseFloat(navStyles.top || "0");
    const navHeight = floatingNav.offsetHeight;
    const clearance = window.innerWidth <= 640 ? 18 : 24;
    const offset = Math.ceil(navTop + navHeight + clearance);

    document.documentElement.style.setProperty("--nav-offset", `${offset}px`);
  };

  const closeFloatingNavMenu = () => {
    if (!floatingNav || !floatingNavToggle) return;

    floatingNav.classList.remove("is-open");
    floatingNavToggle.setAttribute("aria-expanded", "false");
    body.classList.remove("nav-menu-open");
  };

  const toggleFloatingNavMenu = () => {
    if (!floatingNav || !floatingNavToggle) return;

    const nextState = !floatingNav.classList.contains("is-open");
    floatingNav.classList.toggle("is-open", nextState);
    floatingNavToggle.setAttribute("aria-expanded", nextState ? "true" : "false");
    body.classList.toggle("nav-menu-open", nextState && window.innerWidth <= 960);
  };

  const syncActiveTriggers = (view) => {
    spaTriggers.forEach((trigger) => {
      const isActive = trigger.dataset.spaTrigger === view;
      trigger.classList.toggle("is-active", isActive);
    });
  };

  const getSpaViewUrl = (view) => spaRoutes?.[view] || (view === "home" ? "/" : `/${view}/`);

  const activateView = (requestedView, updateUrl = true) => {
    const nextView = validViews.has(requestedView) ? requestedView : defaultView;

    if (!spaPanels.length || nextView === currentView) {
      syncActiveTriggers(nextView);
      return;
    }

    currentView = nextView;
    spaStage?.classList.add("is-transitioning");

    spaPanels.forEach((panel) => {
      const isActive = panel.dataset.spaView === nextView;
      panel.hidden = !isActive;
      panel.setAttribute("aria-hidden", isActive ? "false" : "true");
      panel.classList.toggle("is-active", isActive);

      if (isActive) {
        animateActivePanel(panel);
        panel.querySelectorAll(".reveal").forEach((item) => observeReveal(item));
        if (panel.dataset.spaView === "home") {
          animateHeroTitle(true);
        }
      }
    });

    if (spaStage) {
      spaStage.dataset.currentView = nextView;
    }

    syncActiveTriggers(nextView);

    if (updateUrl) {
      window.history.pushState({ view: nextView }, "", getSpaViewUrl(nextView));
    }

    window.scrollTo({ top: 0, behavior: prefersReducedMotion ? "auto" : "smooth" });

    if (hasAnimeMotion && spaStage) {
      animateMotion(spaStage, {
        filter: ["brightness(1)", "brightness(1.18)", "brightness(1)"],
        duration: 420,
        ease: "inOut(3)",
      });
    }

    window.setTimeout(() => {
      spaStage?.classList.remove("is-transitioning");
    }, 420);
  };

  spaTriggers.forEach((trigger) => {
    trigger.addEventListener("click", (event) => {
      event.preventDefault();
      activateView(trigger.dataset.spaTrigger || defaultView, true);
      if (window.innerWidth <= 960) {
        closeFloatingNavMenu();
      }
    });
  });

  floatingNavToggle?.addEventListener("click", toggleFloatingNavMenu);
  floatingNavToggle?.addEventListener("touchend", (event) => {
    event.preventDefault();
    toggleFloatingNavMenu();
  });

  floatingNavLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth <= 960) {
        closeFloatingNavMenu();
      }
    });
  });

  document.addEventListener("click", (event) => {
    if (!floatingNav || window.innerWidth > 960) {
      return;
    }

    if (!floatingNav.contains(event.target)) {
      closeFloatingNavMenu();
    }
  });

  document.addEventListener("keydown", (event) => {
    if ("Escape" === event.key) {
      closeFloatingNavMenu();
    }
  });

  window.addEventListener("popstate", (event) => {
    activateView(event.state?.view || defaultView, false);
  });

  activateView(defaultView, false);

  syncNavOffset();
  window.addEventListener("resize", () => {
    syncNavOffset();

    if (window.innerWidth > 960) {
      closeFloatingNavMenu();
    } else if (!floatingNav?.classList.contains("is-open")) {
      body.classList.remove("nav-menu-open");
    }
  });
  window.addEventListener("load", syncNavOffset, { once: true });
  if (document.fonts?.ready) {
    document.fonts.ready.then(syncNavOffset).catch(() => {});
  }

  if (!prefersReducedMotion && parallaxItems.length) {
    window.addEventListener("mousemove", (event) => {
      parallaxX = (event.clientX / window.innerWidth - 0.5) * 18;
      parallaxY = (event.clientY / window.innerHeight - 0.5) * 18;

      if (parallaxFrame) {
        return;
      }

      parallaxFrame = window.requestAnimationFrame(() => {
        parallaxItems.forEach((item) => {
          item.style.transform = `translate3d(${parallaxX * -0.28}px, ${parallaxY * -0.35}px, 0)`;
        });
        parallaxFrame = 0;
      });
    });
  }

  if (hasAnimeMotion) {
    const floatingAnimations = [
      {
        target: ".hero-device__halo",
        settings: {
          translateY: [12, -18],
          scale: [0.94, 1.06],
          opacity: [0.5, 0.9],
          duration: 4200,
        },
      },
      {
        target: ".hero-device__frame",
        settings: {
          translateY: [10, -18],
          rotate: ["8deg", "14deg"],
          duration: 5200,
        },
      },
      {
        target: ".ai-drone__bot",
        settings: {
          translateY: [12, -26],
          rotate: ["-2deg", "2deg"],
          duration: 4400,
        },
      },
      {
        target: ".ai-drone__glow",
        settings: {
          scaleX: [0.88, 1.08],
          opacity: [0.35, 0.9],
          duration: 3600,
        },
      },
      {
        target: ".mobile-phone--ios",
        settings: {
          translateY: [10, -16],
          rotate: ["-13deg", "-8deg"],
          duration: 5000,
        },
      },
      {
        target: ".mobile-phone--android",
        settings: {
          translateY: [14, -18],
          rotate: ["8deg", "13deg"],
          duration: 5600,
        },
      },
      {
        target: ".quote-panel__orbs span",
        settings: {
          translateY: staggerMotion([16, -22]),
          translateX: staggerMotion([-10, 12]),
          scale: staggerMotion([0.94, 1.08]),
          opacity: staggerMotion([0.45, 0.92]),
          duration: 4200,
          delay: staggerMotion(220),
        },
      },
      {
        target: ".brand-mark__word",
        settings: {
          opacity: [0.82, 1],
          filter: [
            "drop-shadow(0 0 10px rgba(255,255,255,0.12))",
            "drop-shadow(0 0 18px rgba(111,160,255,0.34))",
          ],
          duration: 3200,
        },
      },
    ];

    floatingAnimations.forEach(({ target, settings }) => {
      if (!document.querySelector(target)) return;

      animateMotion(target, {
        ...settings,
        ease: "inOutSine",
        loop: true,
        alternate: true,
      });
    });
  }

  if (deviceCanvases.length && !prefersReducedMotion && window.THREE) {
    const THREE = window.THREE;

    deviceCanvases.forEach((deviceCanvas) => {
      const variant = deviceCanvas.dataset.deviceCanvas || "ios";
      const renderer = new THREE.WebGLRenderer({
        canvas: deviceCanvas,
        alpha: true,
        antialias: true,
        powerPreference: "high-performance",
      });
      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 100);
      const root = new THREE.Group();
      const particlesGeometry = new THREE.BufferGeometry();
      const particleCount = 220;
      const particlePositions = new Float32Array(particleCount * 3);
      let frameId = 0;

      scene.add(root);
      camera.position.set(0, 0, 9);

      for (let index = 0; index < particleCount; index += 1) {
        particlePositions[index * 3] = (Math.random() - 0.5) * 7.5;
        particlePositions[index * 3 + 1] = (Math.random() - 0.5) * 9;
        particlePositions[index * 3 + 2] = (Math.random() - 0.5) * 5;
      }
      particlesGeometry.setAttribute("position", new THREE.BufferAttribute(particlePositions, 3));

      const isIos = "ios" === variant;
      const accent = isIos ? 0x83b0ff : 0x4fe0ff;
      const accentSecondary = isIos ? 0x0132f1 : 0x44ffb7;
      const ring = new THREE.LineSegments(
        new THREE.WireframeGeometry(
          isIos ? new THREE.TorusKnotGeometry(1.45, 0.16, 140, 18) : new THREE.OctahedronGeometry(1.85, 1)
        ),
        new THREE.LineBasicMaterial({
          color: accent,
          transparent: true,
          opacity: 0.42,
        })
      );
      root.add(ring);

      const halo = new THREE.LineSegments(
        new THREE.WireframeGeometry(
          isIos ? new THREE.IcosahedronGeometry(2.35, 0) : new THREE.TorusGeometry(2.2, 0.08, 18, 72)
        ),
        new THREE.LineBasicMaterial({
          color: accentSecondary,
          transparent: true,
          opacity: 0.24,
        })
      );
      halo.rotation.x = 0.8;
      halo.rotation.y = 0.4;
      root.add(halo);

      const particles = new THREE.Points(
        particlesGeometry,
        new THREE.PointsMaterial({
          color: accentSecondary,
          size: isIos ? 0.06 : 0.07,
          transparent: true,
          opacity: 0.78,
          blending: THREE.AdditiveBlending,
          depthWrite: false,
        })
      );
      root.add(particles);

      const ambient = new THREE.AmbientLight(0xffffff, 0.8);
      const point = new THREE.PointLight(accent, 1.7, 18, 2);
      point.position.set(1.8, 1.2, 5.8);
      scene.add(ambient);
      scene.add(point);

      const resizeDeviceScene = () => {
        const width = deviceCanvas.clientWidth || 320;
        const height = deviceCanvas.clientHeight || 560;
        const ratio = Math.min(window.devicePixelRatio || 1, 1.5);
        renderer.setPixelRatio(ratio);
        renderer.setSize(width, height, false);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
      };

      const animateDeviceScene = (time) => {
        const t = time * 0.001;
        root.rotation.x = Math.sin(t * 0.7) * 0.14;
        root.rotation.y += isIos ? 0.0036 : -0.0032;
        ring.rotation.z = isIos ? t * 0.26 : -t * 0.22;
        halo.rotation.z = isIos ? -t * 0.18 : t * 0.16;
        point.position.x = 1.8 + Math.cos(t * 0.9) * 1.3;
        point.position.y = 1.2 + Math.sin(t * 0.8) * 1.1;

        const positions = particles.geometry.attributes.position.array;
        for (let index = 0; index < particleCount; index += 1) {
          positions[index * 3 + 1] += 0.01 + (index % 7) * 0.00035;
          positions[index * 3] += Math.sin(t + index * 0.45) * 0.0016;

          if (positions[index * 3 + 1] > 4.5) {
            positions[index * 3 + 1] = -4.5;
          }
        }
        particles.geometry.attributes.position.needsUpdate = true;

        renderer.render(scene, camera);
        frameId = window.requestAnimationFrame(animateDeviceScene);
      };

      resizeDeviceScene();
      frameId = window.requestAnimationFrame(animateDeviceScene);
      window.addEventListener("resize", resizeDeviceScene);
      window.addEventListener(
        "beforeunload",
        () => {
          window.cancelAnimationFrame(frameId);
          renderer.dispose();
        },
        { once: true }
      );
    });
  }

  if (counters.length && !prefersReducedMotion) {
    const animatedCounters = new WeakSet();

    const animateCounter = (element) => {
      if (animatedCounters.has(element)) return;
      animatedCounters.add(element);

      const original = element.dataset.counterValue || element.textContent || "";
      const match = original.match(/^([^0-9]*)([0-9]+(?:\.[0-9]+)?)(.*)$/);
      if (!match) return;

      const prefix = match[1];
      const target = Number(match[2]);
      const suffix = match[3];
      const duration = 1200;
      const start = performance.now();

      const tick = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(target * eased * 10) / 10;
        const value = Number.isInteger(target) ? Math.round(current) : current.toFixed(1);
        element.textContent = `${prefix}${value}${suffix}`;

        if (progress < 1) {
          window.requestAnimationFrame(tick);
        } else {
          element.textContent = original;
        }
      };

      window.requestAnimationFrame(tick);
    };

    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            counterObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.55 }
    );

    counters.forEach((counter) => counterObserver.observe(counter));
  }

  if (form && status) {
    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      const data = Object.fromEntries(new FormData(form).entries());
      form.classList.add("is-submitting");
      status.textContent = alenuTheme?.strings?.sending || "Sending...";

      try {
        const response = await fetch(alenuTheme.restUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const payload = await response.json();

        if (!response.ok) {
          throw new Error(payload.message || alenuTheme?.strings?.error || "Something went wrong.");
        }

        form.reset();
        form.classList.remove("is-submitting");
        form.classList.add("is-success");
        status.textContent = payload.message || alenuTheme?.strings?.success || "Request sent.";
      } catch (error) {
        form.classList.remove("is-submitting");
        status.textContent = error.message || alenuTheme?.strings?.error || "Please try again.";
      }
    });
  }

  const createEmptyWorkCard = () => {
    const article = document.createElement("article");
    article.className = "work-card work-card--empty reveal reveal--up";
    article.innerHTML = `
      <div class="work-card__content">
        <span>Works</span>
        <h3>${alenuTheme?.strings?.noWorksFound || "No works found yet."}</h3>
        <p>Try another tab or add more case studies to this category.</p>
      </div>
    `;

    return article;
  };

  const appendWorkItems = (grid, items) => {
    (items || []).forEach((item) => {
      const wrapper = document.createElement("div");
      wrapper.innerHTML = item;
      const card = wrapper.firstElementChild;

      if (card) {
        grid.appendChild(card);
        observeReveal(card);
      }
    });
  };

  worksBrowsers.forEach((browser) => {
    const worksGrid = browser.querySelector("[data-works-grid]");
    const worksLoadMore = browser.querySelector("[data-works-load-more]");
    const worksTabs = Array.from(browser.querySelectorAll("[data-works-tab]"));

    if (!worksGrid || !worksLoadMore) {
      return;
    }

    const syncLoadMore = (payload, fallbackPage = 2) => {
      const hasMore = Boolean(payload?.has_more);
      worksLoadMore.hidden = !hasMore;
      worksLoadMore.disabled = !hasMore;
      worksLoadMore.dataset.nextPage = String(payload?.next_page || fallbackPage);
      worksLoadMore.textContent = alenuTheme?.strings?.loadMore || "Load More Works";
    };

    const fetchWorks = async ({ page = 1, term = "", replace = false }) => {
      const query = new URLSearchParams({ page: String(page) });

      if (term) {
        query.set("term", term);
      }

      const response = await fetch(`${alenuTheme.worksUrl}?${query.toString()}`);
      const payload = await response.json();

      if (!response.ok) {
        throw new Error(alenuTheme?.strings?.loadWorksError || "Could not load more works.");
      }

      if (replace) {
        worksGrid.innerHTML = "";
      }

      if (payload.count) {
        appendWorkItems(worksGrid, payload.html);
      } else if (replace) {
        const emptyCard = createEmptyWorkCard();
        worksGrid.appendChild(emptyCard);
        observeReveal(emptyCard);
      }

      syncLoadMore(payload, page + 1);
      browser.dataset.activeTerm = term;
    };

    worksTabs.forEach((worksTab) => {
      worksTab.addEventListener("click", async () => {
        const nextTerm = worksTab.dataset.term || "";

        worksTabs.forEach((tabButton) => {
          const isActive = tabButton === worksTab;
          tabButton.classList.toggle("is-active", isActive);
          tabButton.setAttribute("aria-selected", isActive ? "true" : "false");
        });

        worksLoadMore.disabled = true;
        worksLoadMore.hidden = false;
        worksLoadMore.textContent = alenuTheme?.strings?.loadingWorks || "Loading...";

        try {
          await fetchWorks({ page: 1, term: nextTerm, replace: true });
        } catch (error) {
          worksLoadMore.disabled = false;
          worksLoadMore.textContent = alenuTheme?.strings?.loadWorksError || "Try again";
        }
      });
    });

    worksLoadMore.addEventListener("click", async () => {
      const nextPage = Number(worksLoadMore.dataset.nextPage || "2");
      const activeTerm = browser.dataset.activeTerm || "";

      worksLoadMore.disabled = true;
      worksLoadMore.textContent = alenuTheme?.strings?.loadingWorks || "Loading...";

      try {
        await fetchWorks({ page: nextPage, term: activeTerm, replace: false });
      } catch (error) {
        worksLoadMore.disabled = false;
        worksLoadMore.textContent = alenuTheme?.strings?.loadWorksError || "Try again";
      }
    });
  });

  const appendBlogItems = (grid, items) => {
    (items || []).forEach((item) => {
      const wrapper = document.createElement("div");
      wrapper.innerHTML = item;
      const card = wrapper.firstElementChild;

      if (card) {
        grid.appendChild(card);
        observeReveal(card);
      }
    });
  };

  blogBrowsers.forEach((browser) => {
    const blogGrid = browser.querySelector("[data-blog-grid]");
    const loadMoreButton = browser.querySelector("[data-blog-load-more]");

    if (!blogGrid || !loadMoreButton) {
      return;
    }

    loadMoreButton.addEventListener("click", async () => {
      const nextPage = Number(loadMoreButton.dataset.nextPage || "2");
      loadMoreButton.disabled = true;
      loadMoreButton.textContent = alenuTheme?.strings?.loadingWorks || "Loading...";

      try {
        const response = await fetch(`${alenuTheme.postsUrl}?page=${nextPage}`);
        const payload = await response.json();

        if (!response.ok) {
          throw new Error(alenuTheme?.strings?.loadPostsError || "Could not load more articles.");
        }

        appendBlogItems(blogGrid, payload.html);

        if (payload.has_more) {
          loadMoreButton.dataset.nextPage = String(payload.next_page || nextPage + 1);
          loadMoreButton.disabled = false;
          loadMoreButton.textContent = alenuTheme?.strings?.loadMorePosts || "Load More Articles";
        } else {
          loadMoreButton.parentElement?.remove();
        }
      } catch (error) {
        loadMoreButton.disabled = false;
        loadMoreButton.textContent = alenuTheme?.strings?.loadPostsError || "Try again";
      }
    });
  });
});
