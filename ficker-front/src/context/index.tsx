"use client";
import { ReactNode, createContext, useState } from "react";

type ContextProps = {
  auth: boolean;
  setAuth: React.Dispatch<React.SetStateAction<any>>;
};

type MainContextProviderProps = {
  children: ReactNode;
};

const MainContext = createContext({} as ContextProps);

export const MainProvider = ({ children }: MainContextProviderProps) => {
  const [auth, setAuth] = useState<boolean>(false);
  return <MainContext.Provider value={{ auth, setAuth }}>{children}</MainContext.Provider>;
};

export default MainContext;
