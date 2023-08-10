import React, { useState } from "react";
import { ContainerOutlined, DesktopOutlined, PieChartOutlined } from "@ant-design/icons";
import type { MenuProps } from "antd";
import { Button, Menu } from "antd";
import Image from "next/image";
import "./styles.scss";
type MenuItem = Required<MenuProps>["items"][number];

function getItem(
  label: React.ReactNode,
  key: React.Key,
  icon?: React.ReactNode,
  children?: MenuItem[],
  type?: "group"
): MenuItem {
  return {
    key,
    icon,
    children,
    label,
    type,
  } as MenuItem;
}

const items: MenuItem[] = [
  getItem("Início", "1", <Image src="/despesas.svg" alt="Logo" width={25} height={25} />),
  getItem("Entradas", "2", <Image src="/bolsa-de-dinheiro.svg" alt="Logo" width={25} height={25} />),
  getItem("Saídas", "3", <Image src="/wallet.svg" alt="Logo" width={25} height={25} />),
  getItem("Meus cartões", "4", <Image src="/cartoes-de-credito.svg" alt="Logo" width={25} height={25} />),
  getItem("Análises", "5", <Image src="/analise.svg" alt="Logo" width={25} height={25} />),
  getItem("Meu perfil", "6", <Image src="/perfil2.svg" alt="Logo" width={25} height={25} />),
  getItem("Sair", "7", <Image src="/exit1.svg" alt="Logo" width={25} height={25} />),
];

const CustomMenu: React.FC = () => {
  const [collapsed, setCollapsed] = useState(false);

  const toggleCollapsed = () => {
    setCollapsed(!collapsed);
  };

  return (
    <div>
      <Menu
        style={{ width: 250, height: "90vh" }}
        defaultSelectedKeys={["1"]}
        mode="inline"
        inlineCollapsed={collapsed}
        items={items}
      />
    </div>
  );
};

export default CustomMenu;
