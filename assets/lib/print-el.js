const getElementFromQuerySelector = (input) => {
    const el = document.querySelector(input);
    if (!el)
        throw new Error("The element can not be found");
    return el;
};
const getHTMLStringFromInput = (input) => {
    if (input instanceof HTMLElement) {
        return input.outerHTML;
    }
    if (input.trim().startsWith("<")) {
        return input;
    }
    return getElementFromQuerySelector(input).outerHTML;
};
const getAllStyleElements = () => {
    return document.querySelectorAll("link[rel=stylesheet], style");
};
const createIFrameElement = () => {
    // Create an iframe for printing content.
    const iFrame = document.createElement("iframe");
    // update style for iFrame to hide this element.
    iFrame.style.width = `100%`;
    iFrame.style.height = `100%`;
    iFrame.style.zIndex = `-10000`;
    iFrame.style.position = "fixed";
    iFrame.style.top = "0";
    iFrame.style.bottom = "0";
    iFrame.style.left = "0";
    iFrame.style.right = "0";
    iFrame.style.visibility = "hidden";
    document.body.append(iFrame);
    return iFrame;
};
function printEl (input, config = {}) {
    if (!input)
        throw new Error("The input element can not be null");
    const { useGlobalStyle = true, pageSize = "A4", margin = 20, css, beforePrint, afterPrint, } = config;
    const printContent = getHTMLStringFromInput(input);
    const iFrame = createIFrameElement();
    const iFrameWindow = iFrame.contentWindow;
    if (iFrameWindow === null)
        throw new Error("Can not get window if iframe");
    iFrameWindow.document.open();
    if (useGlobalStyle)
        getAllStyleElements().forEach((styleEl) => {
            iFrameWindow.document.write(styleEl.outerHTML);
        });
    if (css) {
        iFrameWindow.document.write(`
      <style>
        ${css}
      </style>`);
    }
    iFrameWindow.document.write(`
    <style>
        body, html {
            height: 100%;
            width: 100%;
        }
        .container {
            height: 100%;
            width: 100%;
            display: grid;
            grid-template-columns: 100%;
            grid-template-rows: min-content 5fr min-content;
            gap: 0px 0px;
            grid-auto-flow: row;
            grid-template-areas:
                "head"
                "content"
                "footer";
        }
        .head { grid-area: head; }
        .content { grid-area: content; }
        .footer { grid-area: footer; }
    </style>
    <style>
      @page {
        size: ${pageSize};
        margin: ${margin}px;
      }
    </style>`);
    iFrameWindow.document.write(printContent); // content
    iFrameWindow.document.close();
    iFrameWindow.onafterprint = function () {
        // iFrame.parentNode?.removeChild(iFrame);
        afterPrint && afterPrint();
    };
    iFrameWindow.onload = () => {
        beforePrint && beforePrint();
        iFrameWindow.focus();
        iFrameWindow.print();
    };
};




/** @typedef {Object} PrintConfig
 * @property {string} [pageSize]
 * @property {number} [margin]
 * @property {boolean} [useGlobalStyle]
 * @property {string} [css]
 * @property {Function} [beforePrint]
 * @property {Function} [afterPrint] 
 */