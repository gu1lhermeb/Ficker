import type { ThemeConfig } from "antd";
import { Manrope } from "next/font/google";

const manrope = Manrope({ subsets: ["latin"] });

const theme: ThemeConfig = {
  token: {
    fontSize: 16,
    fontFamily: manrope.style.fontFamily,
  },
};

export default theme;
