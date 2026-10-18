// @flow
import React from 'react';
import styles from './wireframes.scss';

type WireframeProps = {
    componentType: string,
    preset: Object,
};

const resolveAlignX = (align: string, width: number, canvasWidth: number = 240, margin: number = 20): number => {
    if (align === 'center') {
        return (canvasWidth - width) / 2;
    }
    if (align === 'right') {
        return canvasWidth - width - margin;
    }

    return margin;
};

export const HeadlineWireframe = ({preset}: WireframeProps) => {
    const props = (preset && preset.props) || {};
    const align = String(props.text_align || '').toLowerCase();
    const size = String(props.size || '').toLowerCase();
    const tag = String(props.tag || '').toLowerCase();

    const isHero = size === '2xl' || size === 'xl' || tag === 'h1';
    const barHeight = isHero ? 14 : 10;
    const barWidth = isHero ? 160 : 120;
    const barX = resolveAlignX(align, barWidth);

    const subWidth = isHero ? 110 : 80;
    const subX = resolveAlignX(align, subWidth);

    return (
        <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
            <rect className={styles.accentPrimary} height="5" rx="2" width="38" x={ resolveAlignX(align, 38) } y="14" />
            <rect className={styles.titleBar} height={barHeight} rx="3" width={barWidth} x={barX} y="25" />
            <rect className={styles.textPrimary} height="6" rx="2" width={subWidth} x={subX} y="45" />
            <rect className={styles.textSecondary} height="5" rx="2" width={subWidth * 0.65} x={ resolveAlignX(align, subWidth * 0.65) } y="56" />
        </svg>
    );
};

export const TextWireframe = ({preset}: WireframeProps) => {
    const props = (preset && preset.props) || {};
    const cols = String(props.columns || '');
    const isTwoCol = cols.includes('2');

    if (isTwoCol) {
        return (
            <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
                <rect className={styles.titleBar} height="6" rx="2" width="88" x="20" y="18" />
                <rect className={styles.textPrimary} height="5" rx="1.5" width="90" x="20" y="30" />
                <rect className={styles.textSecondary} height="5" rx="1.5" width="84" x="20" y="40" />
                <rect className={styles.textSecondary} height="5" rx="1.5" width="76" x="20" y="50" />
                <rect className={styles.textPrimary} height="5" rx="1.5" width="90" x="126" y="18" />
                <rect className={styles.textSecondary} height="5" rx="1.5" width="86" x="126" y="28" />
                <rect className={styles.textSecondary} height="5" rx="1.5" width="80" x="126" y="38" />
                <rect className={styles.textSecondary} height="5" rx="1.5" width="68" x="126" y="48" />
            </svg>
        );
    }

    return (
        <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
            <rect className={styles.titleBar} height="7" rx="2" width="170" x="20" y="18" />
            <rect className={styles.textPrimary} height="5" rx="1.5" width="196" x="20" y="32" />
            <rect className={styles.textSecondary} height="5" rx="1.5" width="184" x="20" y="43" />
            <rect className={styles.textSecondary} height="5" rx="1.5" width="140" x="20" y="54" />
        </svg>
    );
};

export const ButtonWireframe = ({preset}: WireframeProps) => {
    const props = (preset && preset.props) || {};
    const variant = String(props.button_style || props.variant || '').toLowerCase();
    const isOutline = variant.includes('outline') || variant.includes('secondary');
    const isGhost = variant.includes('ghost') || variant.includes('link');

    const rectClass = isGhost ? styles.accentGhost : (isOutline ? styles.accentOutline : styles.accentPrimary);
    const labelClass = isOutline || isGhost ? styles.accentPrimary : styles.textLight;

    return (
        <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
            <rect
                className={rectClass}
                height="34"
                rx="6"
                strokeDasharray={isGhost ? '3 3' : 'none'}
                strokeWidth={isOutline || isGhost ? '2' : '0'}
                width="140"
                x="50"
                y="26"
            />
            <circle className={labelClass} cx="74" cy="43" r="5" />
            <rect className={labelClass} height="6" rx="2" width="64" x="86" y="40" />
        </svg>
    );
};

export const CardWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.surfaceCard} height="72" rx="5" strokeWidth="1.5" width="200" x="20" y="6" />
        <rect className={styles.mediaBox} height="28" rx="3" strokeWidth="1" width="184" x="28" y="12" />
        <rect className={styles.titleBar} height="7" rx="2" width="90" x="28" y="46" />
        <rect className={styles.textSecondary} height="5" rx="1.5" width="136" x="28" y="58" />
        <rect className={styles.accentPrimary} height="8" rx="2" width="36" x="176" y="64" />
    </svg>
);

export const AlertWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.surfaceMuted} height="54" rx="5" strokeWidth="1.5" width="200" x="20" y="16" />
        <rect className={styles.accentPrimary} height="54" rx="2" width="5" x="20" y="16" />
        <circle className={styles.accentPrimary} cx="38" cy="34" r="6" />
        <rect className={styles.titleBar} height="7" rx="2" width="95" x="52" y="28" />
        <rect className={styles.textSecondary} height="5" rx="1.5" width="145" x="52" y="42" />
        <rect className={styles.textSecondary} height="5" rx="1.5" width="110" x="52" y="52" />
    </svg>
);

export const ImageWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.mediaBox} height="66" rx="5" strokeWidth="1.5" width="180" x="30" y="9" />
        <circle className={styles.accentPrimary} cx="62" cy="29" r="7" />
        <polygon className={styles.textSecondary} points="44,67 96,38 134,67" />
        <polygon className={styles.textPrimary} points="108,67 152,32 196,67" />
    </svg>
);

export const VideoWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.surfaceDark} height="66" rx="5" width="180" x="30" y="9" />
        <circle className={styles.accentPrimary} cx="120" cy="42" r="14" />
        <polygon className={styles.textLight} points="116,35 116,49 128,42" />
    </svg>
);

export const GridWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        {[20, 86, 152].map((x) => (
            <g key={x}>
                <rect className={styles.surfaceCard} height="64" rx="4" strokeWidth="1.2" width="60" x={x} y="10" />
                <rect className={styles.mediaBox} height="22" rx="2" strokeWidth="1" width="50" x={x + 5} y="15" />
                <rect className={styles.titleBar} height="5" rx="1.5" width="36" x={x + 5} y="43" />
                <rect className={styles.textSecondary} height="4" rx="1" width="46" x={x + 5} y="53" />
                <rect className={styles.accentPrimary} height="5" rx="1.5" width="22" x={x + 5} y="62" />
            </g>
        ))}
    </svg>
);

export const GalleryWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        {[20, 86, 152].map((x) => (
            <g key={x}>
                <rect className={styles.mediaBox} height="30" rx="3" strokeWidth="1" width="60" x={x} y="10" />
                <rect className={styles.mediaBox} height="30" rx="3" strokeWidth="1" width="60" x={x} y="45" />
            </g>
        ))}
    </svg>
);

export const OverlayWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.surfaceDark} height="68" rx="5" width="200" x="20" y="8" />
        <rect className={styles.accentPrimary} height="5" rx="2" width="42" x="34" y="26" />
        <rect className={styles.textLight} height="8" rx="2" width="120" x="34" y="36" />
        <rect className={styles.textSecondary} height="5" rx="1.5" width="96" x="34" y="50" />
        <rect className={styles.accentPrimary} height="10" rx="3" width="44" x="34" y="60" />
    </svg>
);

export const QuotationWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.accentPrimary} height="52" rx="2" width="4" x="26" y="16" />
        <rect className={styles.titleBar} height="6" rx="2" width="164" x="40" y="22" />
        <rect className={styles.titleBar} height="6" rx="2" width="142" x="40" y="34" />
        <circle className={styles.accentPrimary} cx="46" cy="56" r="6" />
        <rect className={styles.textSecondary} height="5" rx="1.5" width="70" x="58" y="53" />
    </svg>
);

export const DividerWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <line className={styles.dividerLine} strokeWidth="2" x1="24" x2="104" y1="42" y2="42" />
        <circle className={styles.accentPrimary} cx="120" cy="42" r="5" />
        <line className={styles.dividerLine} strokeWidth="2" x1="136" x2="216" y1="42" y2="42" />
    </svg>
);

export const CountdownWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        {[24, 74, 124, 174].map((x) => (
            <g key={x}>
                <rect className={styles.surfaceCard} height="44" rx="4" strokeWidth="1.2" width="42" x={x} y="20" />
                <rect className={styles.titleBar} height="12" rx="2" width="22" x={x + 10} y="29" />
                <rect className={styles.textSecondary} height="4" rx="1" width="26" x={x + 8} y="49" />
            </g>
        ))}
    </svg>
);

export const IconWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <circle className={styles.surfaceMuted} cx="120" cy="34" r="18" strokeWidth="1.5" />
        <circle className={styles.accentPrimary} cx="120" cy="34" r="8" />
        <rect className={styles.titleBar} height="6" rx="2" width="76" x="82" y="60" />
    </svg>
);

export const ScrollToWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.surfaceCard} height="30" rx="15" strokeWidth="1.5" width="120" x="60" y="27" />
        <circle className={styles.accentPrimary} cx="80" cy="42" r="6" />
        <rect className={styles.titleBar} height="6" rx="2" width="68" x="94" y="39" />
    </svg>
);

export const SectionWireframe = () => (
    <svg className={styles.wireframeSvg} viewBox="0 0 240 85">
        <rect className={styles.surfaceMuted} height="68" rx="4" strokeWidth="1.2" width="208" x="16" y="8" />
        <rect className={styles.surfaceCard} height="48" rx="3" strokeWidth="1" width="92" x="26" y="18" />
        <rect className={styles.surfaceCard} height="48" rx="3" strokeWidth="1" width="92" x="122" y="18" />
        <rect className={styles.titleBar} height="6" rx="1.5" width="60" x="34" y="28" />
        <rect className={styles.textSecondary} height="4" rx="1" width="72" x="34" y="40" />
        <rect className={styles.titleBar} height="6" rx="1.5" width="60" x="130" y="28" />
        <rect className={styles.textSecondary} height="4" rx="1" width="72" x="130" y="40" />
    </svg>
);

export const PRELINE_WIREFRAMES = {
    alert: AlertWireframe,
    button: ButtonWireframe,
    card: CardWireframe,
    countdown: CountdownWireframe,
    divider: DividerWireframe,
    gallery: GalleryWireframe,
    grid: GridWireframe,
    headline: HeadlineWireframe,
    icon: IconWireframe,
    image: ImageWireframe,
    overlay: OverlayWireframe,
    quotation: QuotationWireframe,
    scroll_to: ScrollToWireframe,
    section: SectionWireframe,
    text: TextWireframe,
    video: VideoWireframe,
};
