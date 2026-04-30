jQuery(($) => {
  const tabs = $(".alenu-admin__tab");
  const panels = $(".alenu-admin__panel");

  tabs.on("click", function handleTabClick() {
    const target = $(this).data("tab-target");
    tabs.removeClass("is-active");
    panels.removeClass("is-active");

    $(this).addClass("is-active");
    $(`[data-tab-panel="${target}"]`).addClass("is-active");
  });

  $(".alenu-media-picker").on("click", function handleMediaPicker(event) {
    event.preventDefault();
    const field = $(this).siblings("input");

    const frame = wp.media({
      title: "Choose file",
      button: { text: "Use file" },
      multiple: false,
    });

    frame.on("select", () => {
      const attachment = frame.state().get("selection").first().toJSON();
      field.val(attachment.url);
    });

    frame.open();
  });
});
